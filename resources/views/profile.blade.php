<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="css/navbar_style.css" />
  <link rel="stylesheet" href="css/profile.css" />
  <!-- Firebase App (Core) -->
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js"></script>
  <!-- Firebase Auth -->
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js"></script> 
  <title>U-Save</title>
</head>
<body class="background" id="index-background">
  <nav class="wrapper">
    <a href="/home" style="text-decoration: none">
      <div class="brand">
        <img src="img/logo.png" alt="logo" class="logo" />
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
      <div class="username profile-text"><p id="username">Loading...</p></div>
      <div class="university profile-text">
        <i class="fa-solid fa-graduation-cap"></i>
        <p id="university">Loading...</p>
      </div>
      <div class="email profile-grid">
        <i class="fa-solid fa-envelope icon"></i>
        <p id="email">Loading...</p>
      </div>
      <div class="password profile-grid">
        <i class="fa-solid fa-lock icon"></i>
        <p id="password" class="masked-text">********</p>
        <button onclick="togglePasswordVisibility()">
          <i class="fa-solid fa-eye icon-mata"></i>
        </button>
      </div>
    </div>

    <button onclick="window.location.href='/edit-profile'">Edit Profil</button>
    <button id="logoutButton">Logout</button>
  </div>
  <script type="module" src="js/profile.js"></script>
  <script type="module" src="js/firebase-init.js"></script>
  <script type="module" src="js/authGuard.js"></script>
  <script type="module" src="js/logout.js"></script>
</body>
</html>
<style>
  /* Add your CSS styles here */
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
  }
  .wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #333;
  }
  .brand {
    display: flex;
    align-items: center;
  }
  .logo {
    width: 50px;
    height: auto;
  }
  .namaBrand {
    color: white;
    font-size: 24px;
    margin-left: 10px;
  }
  .navigation {
    list-style-type: none;
    display: flex;
    gap: 20px;
  }
  .navigation li a {
    color: white;
    text-decoration: none;
  }