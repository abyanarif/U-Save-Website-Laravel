<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up - U-Save</title>
    <!-- Font Awesome untuk Ikon -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <!-- Hubungkan ke file CSS asli Anda -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sign_up.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/navbar_style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  </head>
  <body class="background" id="signup-background">
    <nav class="wrapper">
      <div class="brand">
        <img src="{{ asset('img/logo.png') }}" alt="logo" class="logo" />
        <div class="namaBrand">U-SAVE</div>
      </div>
      <div class="menu-icon" id="menu-icon">
        <i class="fa-solid fa-list"></i>
      </div>
    </nav>
    <div class="signup-container">
      <div class="signup-wrapper">
        <h1 class="judul" id="judul_login">Welcome!</h1>
        <h3 class="judul" id="subjudul_login">Sign Up your Account</h3>
        
        <!-- Form dengan ID yang benar -->
        <form id="signup-form">
          <div class="input-group">
            <i class="fa fa-user"></i>
            <!-- FIX: Menambahkan ID yang benar pada setiap input -->
            <input
              type="text"
              id="username-input"
              name="username"
              class="sign-up username"
              placeholder="Username"
              required
            />
          </div>
           <div class="input-group">
            <i class="fa fa-envelope"></i>
            <input
              type="email"
              id="email-input"
              name="email"
              class="sign-up email"
              placeholder="Email"
              required
            />
          </div>
          <div class="input-group">
            <i class="fa fa-university"></i>
            <select name="university" class="sign-up university" id="university-input" required>
              <option value="">Pilih Universitas</option>
            </select>
          </div>
           <div class="input-group">
                <i class="fa fa-phone"></i>
                <input
                  type="tel"
                  id="phone-input"
                  name="phone"
                  class="sign-up phone"
                  placeholder="Phone Number"
                />
            </div>
          <div class="input-group">
            <i class="fa fa-lock"></i>
            <input
              type="password"
              id="password-input"
              name="password"
              class="sign-up password"
              placeholder="Password"
              required
            />
          </div>
          <div class="input-group">
            <i class="fa fa-lock"></i>
            <input
              type="password"
              id="confirmPassword-input"
              name="confirmPassword"
              class="sign-up confirmPassword"
              placeholder="Konfirmasi Password"
              required
            />
          </div>
         
          <button type="submit" class="btn-login">Sign Up</button>
        </form>
        <p class="non_linked_text">
          Already have an account?<a href="/sign-in" class="linked_text"
            >Sign In</a
          >
        </p>
      </div>
    </div>

    <!-- Menghubungkan ke file JavaScript eksternal sebagai module -->
    <script type="module" src="{{ asset('js/firebase-init.js') }}"></script>
    <script type="module" src="{{ asset('js/signup.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
