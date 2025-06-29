<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profil - U-Save</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/navbar_style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}" />
</head>
<body class="background">

    <nav class="wrapper">
        <a href="/home" style="text-decoration: none">
            <div class="brand">
                <img src="{{ asset('img/logo.png') }}" alt="logo" class="logo">
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

    <div class="form-container">
        <div class="form-wrapper">
            <h2>Edit Profil</h2>
            <p>Perbarui informasi akun Anda di bawah ini.</p>
            
            <div id="loadingIndicator" class="text-center my-4">
                <div class="spinner"></div>
                <p>Memuat data...</p>
            </div>

            <form id="edit-profile-form" style="display: none;">
                <div class="input-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username baru" required>
                </div>

                <div class="input-group">
                    <label for="university"><i class="fas fa-university"></i> Universitas</label>
                    <select id="university" name="university" required>
                        <option value="">Memuat universitas...</option>
                    </select>
                </div>
                
                 <div class="input-group">
                    <label for="phone"><i class="fas fa-phone"></i> Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" placeholder="Masukkan nomor telepon">
                </div>

                <div class="input-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" placeholder="Email (tidak dapat diubah)" disabled>
                </div>
                
                <p class="password-note">Kosongkan kolom password jika Anda tidak ingin mengubahnya.</p>

                <div class="input-group">
                    <label for="current-password"><i class="fas fa-key"></i> Password Saat Ini (Wajib jika ingin ganti password)</label>
                    <input type="password" id="current-password" name="current_password" placeholder="Masukkan password Anda saat ini">
                </div>

                <div class="input-group">
                    <label for="new-password"><i class="fas fa-lock"></i> Password Baru</label>
                    <input type="password" id="new-password" name="new_password" placeholder="Password Baru">
                </div>

                <div class="input-group">
                    <label for="confirm-password"><i class="fas fa-check-circle"></i> Konfirmasi Password Baru</label>
                    <input type="password" id="confirm-password" name="confirm_password" placeholder="Konfirmasi Password Baru">
                </div>
                
                <button type="submit" class="btn-save">Simpan Perubahan</button>
                <div id="statusMessage" class="status-message"></div>
            </form>
        </div>
    </div>

    <script type="module" src="{{ asset('js/firebase-init.js') }}"></script>
    <script type="module" src="{{ asset('js/edit-profile.js') }}"></script>
</body>
</html>
