<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Save Profile</title>
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/navbar_style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
</head>
<body class="background" id="index-background">
    <nav class="wrapper">
        <a href="/home" style="text-decoration: none">
            <div class="brand">
                <img src="{{ asset('img/logo.png') }}" alt="logo" class="logo" />
                <div class="namaBrand">U-Save</div>
            </div>
        </a>
        <div class="menu-icon" id="menu-icon">
            <i class="fa-solid fa-list"></i>
        </div>
        <ul class="navigation hidden" id="menu-list">
            <li><a href="/home">Home</a></li>
            <li><a href="/literasi-keuangan">Literasi Keuangan</a></li>
            <li><a href="/budgeting">Budgeting</a></li>
            <li><a href="/profile" class="active">Profile</a></li>
        </ul>
    </nav>

    <div class="starter-container">
        <div class="main-wrapper">
            <div class="photo-profile"></div>
            <!-- Menggunakan ID yang lebih spesifik untuk setiap elemen data -->
            <div class="username profile-text"><p id="profile-username">Memuat...</p></div>
            <div class="university profile-text">
                <i class="fa-solid fa-graduation-cap"></i>
                <p id="profile-university">Memuat...</p>
            </div>
            <div class="email profile-grid">
                <i class="fa-solid fa-envelope icon"></i>
                <p id="profile-email">Memuat...</p>
            </div>
            <!-- FIX: Menambahkan bagian untuk nomor telepon -->
            <div class="phone profile-grid">
                <i class="fa-solid fa-phone icon"></i>
                <p id="profile-phone">Memuat...</p>
            </div>
        </div>

        <a href="/edit-profile" class="">Edit Profil</a>
        <button id="logoutButton" class="">Logout</button>
    </div>

    <!-- Memuat semua script sebagai module di akhir body -->
    <script type="module" src="{{ asset('js/firebase-init.js') }}"></script>
    <script type="module" src="{{ asset('js/authGuard.js') }}"></script>
    <script type="module" src="{{ asset('js/profile.js') }}"></script>
    <script type="module" src="{{ asset('js/logout.js') }}"></script>
</body>
</html>
