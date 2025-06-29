<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="css/navbar_style.css" />
    <link rel="stylesheet" href="css/main.css" />

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
        {{-- <li><a href="/home">Home</a></li>
        <li><a href="/literasi-keuangan">Literasi Keuangan</a></li>
        <li><a href="/sign-in">Budgeting</a></li> --}}
      </ul>
    </nav>
    <div class="starter-container">
      <div class="main-wrapper">
        <h1 class="judul-main" id="judul1-main">U-SAVE</h1>
        <h2 class="judul-main" id="judul2-main">University Save</h2>
        <div class="garis-bawah-main"></div>
        <script src="script.js"></script>
        <br />
        <div class="button-container">
          <a href="/sign-in" class="btn-main" id="button-getStarted"
            >Get Started</a
          >
          <a href="/sign-up" class="btn-main" id="button-signUp">Sign-Up</a>
        </div>
      </div>
    </div>
  </body>
</html>
