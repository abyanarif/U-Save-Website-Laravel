<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> U-Save</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/navbar_style.css">
    <link rel="stylesheet" href="css/home.css">
    
</head>
<body>
    <div class="background-home" id="bg-home">
      <nav class="wrapper">
        <a href="/home" style="text-decoration: none;">
            <div class="brand" >
                <img src="img/logo.png" alt="logo" class="logo">
                <div class="namaBrand">U-Save</div>
            </div>
        </a>
        <div class="menu-icon" id="menu-icon">
            <i class="fa-solid fa-list"></i>
        </div>
            <ul class="navigation hidden" id="menu-list">
                <li><a href="/home" class="active" id="activeText">Home</a></li>
                <li><a href="/literasi-keuangan">Literasi Keuangan</a></li>
                <li><a href="/budgeting">Budgeting</a></li>
                <li><a href="/profile">Profile</a></li>
            </ul>
    </nav>
        <div class="homepage">
            <div class="home-wrapper">
              <p id="user-info" style="color: white;"></p>
                <h1 class="judul" id="judul1">U-Save</h1>
                <div class="bawah-judul">
                    <h2 class="judul" id="judul2">University Save</h2>
                    <div class="garis-bawahhome"></div>
                    <h3 class="keteranganpage" id="keteranganliterasi">U-Save memberikan pengetahuan mengenai literasi keuangan dan menyediakan budgeting otomatis untuk memudahkan Anda mengelola keuangan.</h3>
                </div>
        </div>
        <div class="button-home">
            <div class="btn-home2">
                <div class="box-container">
                  <div class="box-item">
                    <a href="/literasi-keuangan" class="btn-lk">Literasi Keuangan</a>
                    <p class="desc-lk">
                      Literasi keuangan adalah kemampuan individu untuk memahami dan menggunakan berbagai keterampilan keuangan secara efektif, termasuk manajemen keuangan pribadi, penganggaran, dan investasi. Ini merupakan fondasi penting dalam pengelolaan uang yang dapat meningkatkan kesejahteraan finansial seseorang.
                    </p>
                  </div>
                  <div class="box-item">
                    <a href="/budgeting" class="btn-bo">Budgeting Otomatis</a>
                    <p class="desc-bo">
                      U-Save menyediakan fitur budgeting otomatis yang membantu Anda menyusun anggaran untuk kebutuhan penting, seperti uang makan, biaya tempat tinggal, dll. Fitur ini secara otomatis mengelompokkan pengeluaran Anda, memberikan rekomendasi alokasi dana sehingga pengelolaan keuangan menjadi lebih mudah, teratur, dan sesuai prioritas.
                    </p>
                  </div>
                </div>
              </div>     
        </div>
    </div>

    <script src="/js/script.js"></script>
    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore-compat.js"></script>
    <script type="module" src="/js/firebase-init.js"></script>
    <script type="module" src="/js/authGuard.js"></script>
</body>
</html>