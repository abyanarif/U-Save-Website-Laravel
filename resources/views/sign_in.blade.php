<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/sign_in.css" />
    <link rel="stylesheet" href="css/navbar_style.css" />
    <link rel="stylesheet" href="css/login.css" />
    <title>Login Page</title>
</head>
<body class="background-signIn" id="body_main">
    <div class="fContainer-login">
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

        <div class="login-container">
            <div class="login-wrapper">
                <h1 class="judul_login" id="judul_login">Welcome!</h1>
                <h3 class="judul_login" id="subjudul_login">Login your Account</h3>

                <!-- Login Form -->
                <form id="login-form">
                    <div class="input-group">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" class="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" class="password" placeholder="Password" required>
                    </div>
                    <p><a href="#" class="linked_text">Forgot Password?</a></p> 
                    <button type="submit" class="btn-login">Sign In</button>
                </form>

                <p class="non_linked_text">Don’t have an account? 
                    <a href="/sign-up" class="linked_text">Sign Up</a>
                </p>   
            </div>      
        </div>
    </div>

    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>

    <script type='module' src="/js/firebase-init.js"></script>
    <script type="module" src="/js/signin.js"></script>
</body>
</html>
