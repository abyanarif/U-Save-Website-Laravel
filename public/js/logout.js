import { getAuth, signOut } from "firebase/auth";
import { app } from "./firebase-init.js";

const auth = getAuth(app);

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
    } else {
        console.warn("Tombol logout tidak ditemukan di halaman.");
    }
});
