<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Literasi Keuangan | U-Save</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="css/navbar_style.css" />
    <link rel="stylesheet" href="css/literasi_keuangan.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap"
      rel="stylesheet"
    />
    <style>
      /* Smooth scroll behavior */
      html {
        scroll-behavior: smooth;
      }
    </style>
  </head>
  <body id="body_literasiKeuangan">
    <div class="literasiContainer">
      <nav class="wrapper">
        <a href="/home" style="text-decoration: none">
          <div class="brand">
            <img src="img/logo.png" alt="Logo U-Save" class="logo" />
            <div class="namaBrand">U-Save</div>
          </div>
        </a>
        <button class="menu-icon" id="menu-icon" aria-label="Toggle menu">
          <i class="fa-solid fa-bars"></i>
        </button>
        <ul class="navigation hidden" id="menu-list">
          <li><a href="/home">Home</a></li>
          <li>
            <a href="/literasi-keuangan" class="active"
              >Literasi Keuangan</a
            >
          </li>
          <li><a href="/budgeting">Budgeting</a></li>
          <li><a href="/profile">Profile</a></li>
        </ul>
      </nav>

      <div class="literasi-content-sections">
        <section class="literasi-keuangan-content1">
          <main>
            <div class="literasi_utama">
              <div class="judul-wrapper">
                <div class="judul-subWrapper">
                  <h1 class="judul-literasi">U-SAVE</h1>
                  <div class="bawah-judul">
                    <h2 class="judul-literasi">University Save</h2>
                    <div class="garis-bawah"></div>
                    <h3 class="keterangan-literasi">Literasi<br />Keuangan</h3>
                  </div>
                </div>
              </div>
              <img
                src="img/babi.png"
                alt="Ilustrasi Celengan"
                class="gambar-babi"
              />
            </div>

            <div class="button-container-literasi">
              <a href="#literasi" class="btn-literasi">
                <div class="button-literasi-wrapper">
                  <img
                    src="img/icon-buku.png"
                    alt="Pemahaman Dasar"
                    class="icon-literasi"
                  />
                  <p>Pemahaman Dasar Keuangan Pribadi</p>
                </div>
              </a>

              <a href="#tabungan" class="btn-literasi">
                <div class="button-literasi-wrapper">
                  <img
                    src="img/icon-celengan.png"
                    alt="Tabungan"
                    class="icon-literasi"
                  />
                  <p>Tabungan dan Pinjaman</p>
                </div>
              </a>

              <a href="#investasi" class="btn-literasi">
                <div class="button-literasi-wrapper">
                  <img
                    src="img/icon-investasi.png"
                    alt="Investasi"
                    class="icon-literasi"
                  />
                  <p>Investasi</p>
                </div>
              </a>

              <a href="#asuransi" class="btn-literasi">
                <div class="button-literasi-wrapper">
                  <img
                    src="img/icon-id_card.png"
                    alt="Asuransi"
                    class="icon-literasi"
                  />
                  <p>Asuransi</p>
                </div>
              </a>

              <a href="#perencanaan" class="btn-literasi">
                <div class="button-literasi-wrapper">
                  <img
                    src="img/icon-kalender.png"
                    alt="Perencanaan"
                    class="icon-literasi"
                  />
                  <p>Perencanaan Keuangan Jangka Panjang</p>
                </div>
              </a>
            </div>
          </main>
        </section>

        <!-- Bagian Pemahaman Dasar - Konten akan dimuat oleh JavaScript -->
        <section id="literasi" class="content-sections">
          <main class="content-literasi">
            <div class="box-literasi"></div>
            <div class="div-gambar-literasi">
              <img class="gambar-literasi" id="img-pemahaman-dasar" src="" alt="Memuat gambar...">
            </div>
            <div class="keterangan-content-literasi" id="content-pemahaman-dasar">
              <h1>Memuat...</h1>
            </div>
          </main>
        </section>

        <!-- Bagian Tabungan dan Pinjaman - Konten akan dimuat oleh JavaScript -->
        <section id="tabungan" class="content-sections">
          <main class="content-tabungan">
            <div class="keterangan-content-tabungan" id="content-tabungan">
              <h1>Memuat...</h1>
            </div>
          </main>
        </section>

        <!-- Bagian Investasi - Konten akan dimuat oleh JavaScript -->
        <section id="investasi" class="content-sections">
          <main class="content-investasi">
            <div class="keterangan-content-investasi" id="content-investasi">
              <img class="gambar-investasi" id="img-investasi" src="" alt="Memuat gambar...">
              <div class="div-judul-investasi">
                <h1>Memuat...</h1>
              </div>
              <div><!-- Konten detail investasi akan masuk ke sini oleh JS --></div>
            </div>
          </main>
        </section>

        <!-- Bagian Asuransi - Konten akan dimuat oleh JavaScript -->
        <section id="asuransi" class="content-sections">
          <main class="content-asuransi">
            <div class="keterangan-content-asuransi" id="content-asuransi">
              <h1>Memuat...</h1>
            </div>
          </main>
        </section>

        <!-- Bagian Perencanaan - Konten akan dimuat oleh JavaScript -->
        <section id="perencanaan" class="content-sections">
          <main class="content-perencanaan">
            <div class="div-gambar-perencanaan">
              <img src="" alt="Memuat gambar..." class="gambar-perencanaan" id="img-perencanaan">
            </div>
            <div class="keterangan-content-perencanaan" id="content-perencanaan">
              <h1>Memuat...</h1>
            </div>
          </main>
        </section>
      </div>
    </div>
    
    <!-- FIX: Menambahkan type="module" pada script yang menggunakan import/export -->
    <script src="js/firebase-init.js" type="module"></script>
    <script src="js/authGuard.js" type="module"></script>
    <!-- BARU: JavaScript untuk mengambil dan menampilkan artikel secara dinamis -->
    <!-- Pastikan file ini ada di public/js/literasi-dinamis.js -->
    <script src="js/literasi-dinamis.js" type="module"></script>
  </body>
</html>
