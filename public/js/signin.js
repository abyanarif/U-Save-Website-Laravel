// Pastikan import dari firebase-init.js
import { auth, db } from "./firebase-init.js"; // Sesuaikan path-nya

import { signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

import {
    enableNetwork,
    collection,
    getDocs,
    query,
    limit,
} from "https://www.gstatic.com/firebasejs/10.11.0/firebase-firestore.js";

// Tes koneksi Firestore
enableNetwork(db)
    .then(() => {
        const testQuery = query(collection(db, "test"), limit(1));
        return getDocs(testQuery);
    })
    .then(() => {
        console.log("Firestore ONLINE");
    })
    .catch((err) => {
        console.error("Firestore ERROR:", err.message);
    });

// Login logic
const loginForm = document.getElementById("login-form");

loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const email = loginForm.email.value.trim();
    const password = loginForm.password.value;

    if (!email || !password) {
        alert("Harap isi email dan password.");
        return;
    }

    signInWithEmailAndPassword(auth, email, password)
        .then(() => {
            alert("Login berhasil!");
            window.location.href = "/home";
        })
        .catch((error) => {
            console.error("Login error: ", error);
            alert("Login gagal: " + error.message);
        });
});
