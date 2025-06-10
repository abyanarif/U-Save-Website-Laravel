import { app } from "./firebase-init.js";
import {
    getAuth,
    onAuthStateChanged,
    signOut,
} from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-firestore.js";

const auth = getAuth(app);
const db = getFirestore(app); // hanya jika kamu butuh Firestore

// Fungsi cek login dan ambil data user
onAuthStateChanged(auth, async (user) => {
    if (!user) {
        window.location.href = "/sign-in";
        return;
    }

    console.log("User sedang login:", user);

    try {
        const uid = user.uid;
        const response = await fetch(`/api/user-profile?firebase_uid=${uid}`);
        if (!response.ok) throw new Error("User tidak ditemukan di backend");

        const { user: data } = await response.json();

        // Tampilkan user
        const usernameEl = document.querySelector(".username p");
        const emailEl = document.querySelector(".email p");
        const universityEl = document.querySelector(".university p");

        if (usernameEl) usernameEl.innerText = data.username || "";
        if (emailEl) emailEl.innerText = data.email || "";
        if (universityEl) universityEl.innerText = data.university || "";
    } catch (error) {
        console.error("Gagal mengambil data user:", error);
    }
});

// Logout handler
document.addEventListener("DOMContentLoaded", () => {
    const logoutButton = document.querySelector("#logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", async () => {
            try {
                await signOut(auth);
                window.location.href = "/sign-in";
            } catch (error) {
                console.error("Logout gagal:", error);
                alert("Gagal logout. Silakan coba lagi.");
            }
        });
    }
});

// Toggle tampilkan password (opsional)
function togglePasswordVisibility() {
    const elements = document.querySelectorAll(".masked-text");

    elements.forEach((element) => {
        if (element.textContent === element.dataset.original) {
            element.textContent = "•".repeat(element.dataset.original.length);
        } else {
            element.textContent = element.dataset.original;
        }
    });
}
