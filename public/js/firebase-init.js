import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-firestore.js";

const firebaseConfig = {
    apiKey: "AIzaSyCbPs80n0sBuc5IQOsu7csRwCAXByaqeVg",
    authDomain: "u-save-ca249.firebaseapp.com",
    projectId: "u-save-ca249",
    storageBucket: "u-save-ca249.appspot.com",
    messagingSenderId: "476323989664",
    appId: "1:476323989664:web:118d823e20dd6ae3d061c5",
    measurementId: "G-BZE3X8RSD8",
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app);

export { app, auth, db };
