// Mengimpor fungsi yang diperlukan dari Firebase SDK
import {
    getAuth,
    createUserWithEmailAndPassword,
} from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
// Mengimpor 'app' dari file inisialisasi Firebase Anda
import { app } from "./firebase-init.js";

const auth = getAuth(app);
const form = document.getElementById("signup-form"); // Pastikan form Anda memiliki id="signup-form"

// 🟢 Load daftar universitas saat halaman dimuat
document.addEventListener("DOMContentLoaded", async function () {
    const selectElement = document.getElementById("university-input"); // Pastikan elemen select Anda memiliki id="university-input"
    if (!selectElement) return;

    try {
        const response = await fetch("/get-universities");
        if (!response.ok) throw new Error("Gagal memuat daftar universitas.");

        const universities = await response.json();

        selectElement.innerHTML =
            '<option value="">-- Pilih Universitas --</option>';

        universities.forEach((univ) => {
            const option = document.createElement("option");
            option.value = univ.id;
            option.textContent = univ.name;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error("Gagal memuat universitas:", error);
        selectElement.innerHTML = '<option value="">Gagal memuat data</option>';
        alert(
            "Gagal memuat data universitas. Silakan coba muat ulang halaman."
        );
    }
});

// 🔵 Proses Signup (Logika Diperbarui)
if (form) {
    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        // FIX: Mengambil elemen dari DOM terlebih dahulu
        const usernameInput = document.getElementById("username-input");
        const emailInput = document.getElementById("email-input");
        const passwordInput = document.getElementById("password-input");
        const confirmPasswordInput = document.getElementById(
            "confirmPassword-input"
        );
        const universityInput = document.getElementById("university-input");
        const phoneInput = document.getElementById("phone-input");

        // FIX: Menambahkan pengecekan untuk memastikan semua elemen ada sebelum membaca 'value'
        if (
            !usernameInput ||
            !emailInput ||
            !passwordInput ||
            !confirmPasswordInput ||
            !universityInput ||
            !phoneInput
        ) {
            alert(
                "Error: Terjadi kesalahan pada form. Pastikan semua ID input di file HTML sudah benar."
            );
            console.error(
                "Satu atau lebih elemen form tidak ditemukan. Periksa ID: username-input, email-input, password-input, confirmPassword-input, university-input, phone-input."
            );
            return;
        }

        const username = usernameInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const universityId = universityInput.value;
        const phone = phoneInput.value;

        // Validasi Sederhana di Frontend
        if (password !== confirmPassword) {
            alert("Password dan konfirmasi password tidak cocok!");
            return;
        }
        if (username.length < 4) {
            alert("Username minimal 4 karakter.");
            return;
        }
        if (!universityId) {
            alert("Silakan pilih universitas Anda.");
            return;
        }

        try {
            // Langkah 1: Buat pengguna di Firebase Authentication
            const userCredential = await createUserWithEmailAndPassword(
                auth,
                email,
                password
            );
            const firebaseUser = userCredential.user;

            console.log("User berhasil dibuat di Firebase:", firebaseUser.uid);

            // Langkah 2: Dapatkan ID Token dari pengguna yang baru dibuat
            const idToken = await firebaseUser.getIdToken();

            // Langkah 3: Kirim token DAN data tambahan ke backend Laravel untuk sinkronisasi
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
                body: JSON.stringify({
                    username: username,
                    university_id: universityId || null,
                    phone: phone || null,
                }),
            });

            // Tangani respons dari server Laravel
            if (!response.ok) {
                const errorData = await response.json();
                await firebaseUser.delete();
                console.error("Gagal sinkronisasi, user Firebase dihapus.");
                throw new Error(
                    errorData.message ||
                        "Gagal menyimpan data pengguna di server."
                );
            }

            const result = await response.json();
            console.log(
                "Pengguna berhasil disinkronkan dengan database lokal:",
                result.user
            );

            alert("Akun berhasil dibuat dan disinkronkan!");
            window.location.href = "/home";
        } catch (error) {
            console.error("Error saat pendaftaran:", error);
            let errorMessage = error.message;

            if (error.code === "auth/email-already-in-use") {
                errorMessage =
                    "Email ini sudah terdaftar. Silakan gunakan email lain.";
            } else if (error.code === "auth/weak-password") {
                errorMessage =
                    "Password terlalu lemah. Gunakan minimal 6 karakter.";
            }

            alert("Pendaftaran Gagal: " + errorMessage);
        }
    });
} else {
    console.error(
        "Error: Form pendaftaran dengan ID 'signup-form' tidak ditemukan di halaman ini."
    );
}
