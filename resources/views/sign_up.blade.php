<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/sing_up.css" />
    <link rel="stylesheet" href="/css/navbar_style.css" />
    <link rel="stylesheet" href="/css/login.css" />

    <title>Login Page</title>

  </head>
  <body class="background" id="signup-background">
    <nav class="wrapper">
      <div class="brand">
        <img src="img/logo.png" alt="logo" class="logo" />
        <div class="namaBrand">U-SAVE</div>
      </div>
      <div class="menu-icon" id="menu-icon">
        <i class="fa-solid fa-list"></i>
      </div>
      {{-- <ul class="navigation hidden" id="menu-list">
        <li><a href="/home">Home</a></li>
        <li><a href="/literasi-keuangan">Literasi Keuangan</a></li>
        <li><a href="/budgeting">Budgeting</a></li>
      </ul> --}}
    </nav>
    <div class="signup-container">
      <div class="signup-wrapper">
        <h1 class="judul" id="judul_login">Welcome!</h1>
        <h3 class="judul" id="subjudul_login">Sign Up your Account</h3>
        <form id="signup-form">
          <div class="input-group">
            <i class="fa fa-user"></i>
            <input
              type="text"
              name="username"
              class="sign-up username"
              placeholder="Username"
            />
          </div>
          <div class="input-group">
            <i class="fa fa-university"></i>
            <select name="university" class="sign-up university" id="university-input" required>
              <option value="">Pilih Universitas</option>
            </select>
          </div>          
          <div class="input-group">
            <i class="fa fa-lock"></i>
            <input
              type="password"
              name="password"
              class="sign-up password"
              placeholder="Password"
            />
          </div>
          <div class="input-group">
            <i class="fa fa-lock"></i>
            <input
              type="password"
              name="confirmPassword"
              class="sign-up confirmPassword"
              placeholder="Konfirmasi Password"
            />
          </div>
          <div class="input-group">
            <i class="fa fa-envelope"></i>
            <input
              type="mail"
              name="email"
              class="sign-up email"
              placeholder="Email"
            />
          </div>
          <button type="submit" class="btn-login">Sign In</button>
        </form>
        <p class="non_linked_text">
          Already have an account?<a href="/sign-in" class="linked_text"
            >Sign In</a
          >
        </p>
      </div>
    </div>
    <!-- Tambahkan di akhir sebelum </body> -->
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore-compat.js"></script>

    <script type="module" src="js/firebase-init.js"></script>
    <script type="module" src="js/signup.js"></script>
    <script src="/js/script.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
