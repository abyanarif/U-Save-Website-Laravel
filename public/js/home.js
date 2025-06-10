firebase.auth().onAuthStateChanged((user) => {
    if (user) {
        // Pengguna sedang login
        const email = user.email;
        document.getElementById(
            "user-info"
        ).innerText = `Selamat datang, ${email}`;
    } else {
        // Tidak ada user login, arahkan ke login page
        window.location.href = "/sign_in";
    }
});
