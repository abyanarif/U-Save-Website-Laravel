// Pastikan import dari firebase-init.js dan Firebase SDK
import { app, auth } from "./firebase-init.js";
import { signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

// Ambil elemen form login
const loginForm = document.getElementById("login-form");

if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        // FIX: Mengembalikan cara pengambilan data form ke metode semula
        const email = loginForm.email.value.trim();
        const password = loginForm.password.value;

        // Periksa apakah elemen input ada sebelum membaca nilainya
        if (!email || !password) {
            alert("Harap isi email dan password.");
            return;
        }

        try {
            // Langkah 1: Login pengguna dengan Firebase Authentication
            const userCredential = await signInWithEmailAndPassword(
                auth,
                email,
                password
            );
            const firebaseUser = userCredential.user;

            console.log(
                "Pengguna berhasil login di Firebase:",
                firebaseUser.uid
            );

            // Langkah 2: Dapatkan ID Token dari pengguna yang sudah login
            const idToken = await firebaseUser.getIdToken();

            // Langkah 3: Kirim token ke backend Laravel untuk proses sinkronisasi
            const response = await fetch("/sync-user", {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${idToken}`,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
            });

            // Tangani respons dari server Laravel
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(
                    errorData.message ||
                        "Gagal melakukan sinkronisasi saat login."
                );
            }

            const result = await response.json();
            console.log("Login dan sinkronisasi berhasil:", result);

            // Langkah 4: Arahkan ke halaman beranda setelah login dan sinkronisasi berhasil
            window.location.href = "/home";
        } catch (error) {
            console.error("Login atau sinkronisasi gagal: ", error);

            let errorMessage = error.message;
            if (error.message.includes("CSRF token mismatch")) {
                errorMessage =
                    "Terjadi masalah sesi. Silakan muat ulang halaman dan coba lagi.";
            } else if (
                error.code === "auth/invalid-credential" ||
                error.code === "auth/wrong-password" ||
                error.code === "auth/user-not-found"
            ) {
                errorMessage = "Email atau password yang Anda masukkan salah.";
            }

            alert("Login gagal: " + errorMessage);
        }
    });
} else {
    console.error(
        "Form dengan ID 'login-form' tidak ditemukan di halaman ini."
    );
}
