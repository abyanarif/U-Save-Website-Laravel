<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/navbar_style.css">
    <title>Edit Profil</title>
</head>
<body class="background">

    <nav class="wrapper">
        <a href="/home" style="text-decoration: none">
            <div class="brand">
                <img src="img/logo.png" alt="logo" class="logo">
                <div class="namaBrand">U-Save</div>
            </div>
        </a>
        <ul class="navigation">
            <li><a href="/home">Home</a></li>
            <li><a href="/literasi-keuangan">Literasi Keuangan</a></li>
            <li><a href="/budgeting">Budgeting</a></li>
            <li><a href="/profile">Profile</a></li>
        </ul>
    </nav>

    <div class="starter-container">
        <div class="main-wrapper">
            <h2>Edit Profil</h2>

            <form id="edit-profile-form">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <label for="university">Universitas</label>
                    <input type="text" id="university" name="university" placeholder="Universitas" required>
                </div>
                <div class="input-group">
                    <label for="new-password">Password Baru</label>
                    <input type="password" id="new-password" name="new-password" placeholder="Password Baru">
                </div>
                <div class="input-group">
                    <label for="confirm-password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Konfirmasi Password Baru">
                </div>
                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore-compat.js"></script>

    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCbPs80n0sBuc5IQOsu7csRwCAXByaqeVg",
            authDomain: "u-save-ca249.firebaseapp.com",
            projectId: "u-save-ca249",
            storageBucket: "u-save-ca249.firebasestorage.app",
            messagingSenderId: "476323989664",
            appId: "1:476323989664:web:118d823e20dd6ae3d061c5",
            measurementId: "G-BZE3X8RSD8"
        };

        firebase.initializeApp(firebaseConfig);

        const user = firebase.auth().currentUser;

        if (user) {
            const userDoc = firebase.firestore().collection("users").doc(user.uid);
            userDoc.get().then((doc) => {
                if (doc.exists) {
                    const data = doc.data();
                    document.getElementById("username").value = data.username;
                    document.getElementById("university").value = data.university;
                }
            });
        }

        // Handle the form submission
        const form = document.getElementById("edit-profile-form");

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const newUsername = form.username.value;
            const newUniversity = form.university.value;
            const newPassword = form["new-password"].value;
            const confirmPassword = form["confirm-password"].value;

            const user = firebase.auth().currentUser;

            if (newPassword && newPassword !== confirmPassword) {
                alert("Password dan konfirmasi password tidak cocok!");
                return;
            }

            // Update Firestore with new username and university
            firebase.firestore().collection("users").doc(user.uid).update({
                username: newUsername,
                university: newUniversity,
            })
            .then(() => {
                // If password is provided, update the password in Firebase Auth
                if (newPassword) {
                    user.updatePassword(newPassword).then(() => {
                        alert("Profil berhasil diperbarui!");
                    }).catch((error) => {
                        alert("Error mengubah password: " + error.message);
                    });
                } else {
                    alert("Profil berhasil diperbarui!");
                }
            })
            .catch((error) => {
                alert("Error memperbarui profil: " + error.message);
            });
        });
    </script>
</body>
</html>
