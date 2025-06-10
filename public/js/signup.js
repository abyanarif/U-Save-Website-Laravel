import {
    getAuth,
    createUserWithEmailAndPassword,
} from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
import {
    getFirestore,
    doc,
    getDoc,
    setDoc,
    serverTimestamp,
} from "https://www.gstatic.com/firebasejs/10.11.0/firebase-firestore.js";
import { app } from "./firebase-init.js";

const auth = getAuth(app);
const db = getFirestore(app);
const form = document.getElementById("signup-form");

// 🟢 Load daftar universitas saat halaman dimuat
document.addEventListener("DOMContentLoaded", async function () {
    const selectElement = document.getElementById("university-input");

    try {
        const response = await fetch("/get-universities"); // Ganti jika pakai route lain
        const universities = await response.json();

        universities.forEach((univ) => {
            const option = document.createElement("option");
            option.value = univ.id;
            option.textContent = univ.name;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error("Gagal memuat universitas:", error);
        alert("Gagal memuat data universitas");
    }
});

// 🔵 Proses Signup
form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const username = form.username.value.trim();
    const email = form.email.value.trim();
    const password = form.password.value;
    const confirmPassword = form.confirmPassword.value;
    const universityId = form.university.value; // ambil ID universitas

    if (password !== confirmPassword) {
        alert("Password dan konfirmasi password tidak cocok!");
        return;
    }

    if (username.length < 4) {
        alert("Username minimal 4 karakter.");
        return;
    }

    try {
        // Cek username di Firestore
        const usernameDocRef = doc(db, "usernames", username);
        const usernameDoc = await getDoc(usernameDocRef);

        if (usernameDoc.exists()) {
            throw new Error("Username sudah digunakan.");
        }

        // Buat akun Firebase
        const userCredential = await createUserWithEmailAndPassword(
            auth,
            email,
            password
        );
        const user = userCredential.user;

        // Simpan ke Firestore
        await Promise.all([
            setDoc(doc(db, "usernames", username), { email }),
            setDoc(doc(db, "users", user.uid), {
                username,
                email,
                role: "user",
                universityId: universityId || null,
                createdAt: serverTimestamp(),
            }),
        ]);

        // Simpan ke MySQL via Laravel backend
        await fetch("/store-user", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                uid: user.uid,
                email,
                username,
                university_id: universityId || null,
            }),
        });

        alert("Akun berhasil dibuat!");
        window.location.href = "/home";
    } catch (error) {
        console.error("Error saat signup:", error);
        alert("Error: " + error.message);
    }
});
