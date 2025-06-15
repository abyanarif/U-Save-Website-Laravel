// FIX: Mengimpor 'signOut' untuk fungsi logout
import {
    getAuth,
    onAuthStateChanged,
    signOut,
} from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
// Mengimpor 'app' dari file inisialisasi Firebase Anda
import { app } from "./firebase-init.js";

const auth = getAuth(app);

// --- Fungsi untuk memeriksa status login dan mengambil data profil ---
onAuthStateChanged(auth, async (user) => {
    if (!user) {
        console.log(
            "Tidak ada user yang login, mengarahkan ke halaman sign-in..."
        );
        window.location.href = "/sign-in";
        return;
    }

    console.log("User sedang login:", user.uid);

    try {
        const uid = user.uid;
        // Panggil API backend Anda untuk mendapatkan detail profil dari database MySQL
        const response = await fetch(`/api/user-profile?firebase_uid=${uid}`);

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(
                `Gagal mengambil profil dari server. Status: ${response.status}. Respons Server: ${errorText}`
            );
        }

        const responseText = await response.text();
        if (!responseText) {
            throw new Error("Data profil yang diterima dari server kosong.");
        }

        const data = JSON.parse(responseText);

        if (!data || !data.username) {
            throw new Error(
                "Data profil yang diterima dari server tidak valid."
            );
        }

        console.log("Data profil diterima:", data);

        // Tampilkan data pengguna ke elemen HTML
        const usernameEl = document.getElementById("profile-username");
        const emailEl = document.getElementById("profile-email");
        const universityEl = document.getElementById("profile-university");
        const phoneEl = document.getElementById("profile-phone");

        if (usernameEl) usernameEl.innerText = data.username;
        if (emailEl) emailEl.innerText = data.email;
        if (universityEl)
            universityEl.innerText = data.university || "Belum diatur";
        if (phoneEl) phoneEl.innerText = data.phone || "Belum diatur";
    } catch (error) {
        console.error("Gagal mengambil atau menampilkan data profil:", error);
        // Tampilkan pesan error kepada pengguna
        document.getElementById("profile-username").innerText =
            "Gagal Memuat Data";
        document.getElementById("profile-university").innerText = "Error";
        document.getElementById("profile-email").innerText = "Error";
        const phoneEl = document.getElementById("profile-phone");
        if (phoneEl) phoneEl.innerText = "Error";
    }
});

// --- FIX: Menambahkan logika untuk tombol Logout ---
document.addEventListener("DOMContentLoaded", () => {
    const logoutButton = document.getElementById("logoutButton");

    // Pastikan tombol logout ada di halaman ini
    if (logoutButton) {
        logoutButton.addEventListener("click", async () => {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                try {
                    await signOut(auth);
                    console.log("Logout berhasil.");
                    // Arahkan ke halaman sign-in setelah logout berhasil
                    window.location.href = "/sign-in";
                } catch (error) {
                    console.error("Logout gagal:", error);
                    alert("Gagal logout. Silakan coba lagi.");
                }
            }
        });
    }
});
