<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Budgeting | U-Save</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/navbar_style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/budgeting.css') }}" />
    <style>
      .results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
      }
      #uang-saku-display {
        background-color: #15b7b9;
        color: white;
      }
      #uang-saku-display h3, #uang-saku-display .hasil-amount {
        color: white;
      }
      /* FIX: CSS agar tombol "Lainnya" terlihat sama seperti label kategori lain */
      .category-button {
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        font: inherit;
        color: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        width: 100%;
        height: 100%;
        justify-content: center;
      }
    </style>
  </head>
  <body id="body_budgeting">
    <div class="fContainer-budgeting">
      <nav class="wrapper" id="budgeting-nav">
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
          <li><a href="/budgeting" class="active">Budgeting</a></li>
          <li><a href="/profile">Profile</a></li>
        </ul>
      </nav>

      <div class="budgeting-content-sections">
        <section class="budgeting-content1">
          <main class="budgeting-main">
            <div class="budgeting-judul">
              <h1 class="judul-budgeting">U-SAVE</h1>
              <p class="judul-budgeting">University Save</p>
              <div class="garis-bawah-budgeting"></div>
              <h2>Budgeting Otomatis</h2>
            </div>
            <div class="button-budgeting">
              <a href="#buat" class="button-buat"><div class="div-button">Buat</div></a>
              <a href="#cek" class="button-cek"><div class="div-button">Cek</div></a>
            </div>
          </main>
        </section>

        <section class="budgeting-content-buat" id="buat">
          <main class="main-input">
            <h1 class="label-budgeting">Isi Disini</h1>
            <form id="budgetingForm" class="budgeting-form" onsubmit="return false;">
              <input type="text" placeholder="Uang Saku" id="uangSakuInput" class="rupiah-input" />
              <div class="form-group">
                <label for="asalKota">Asal Kota:</label>
                <select id="asalKota" name="asalKota" class="scrollable" required>
                  <option value="">-- Pilih Kota --</option>
                </select>
              </div>
              <div class="form-group">
                <label>Alokasikan untuk:</label>
                <div class="budget-categories">
                  <div class="category-item"><input type="checkbox" id="kos" name="budgetCategory" value="kos"><label for="kos"><i class="fas fa-home"></i><span class="checkbox-text">Kos</span></label></div>
                  <div class="category-item"><input type="checkbox" id="makan" name="budgetCategory" value="makan"><label for="makan"><i class="fas fa-utensils"></i><span class="checkbox-text">Makan</span></label></div>
                  <div class="category-item"><input type="checkbox" id="hiburan" name="budgetCategory" value="hiburan"><label for="hiburan"><i class="fas fa-gamepad"></i><span class="checkbox-text">Hiburan</span></label></div>
                  <div class="category-item"><input type="checkbox" id="internet" name="budgetCategory" value="internet"><label for="internet"><i class="fas fa-wifi"></i><span class="checkbox-text">Internet</span></label></div>
                  <div class="category-item"><input type="checkbox" id="darurat" name="budgetCategory" value="darurat"><label for="darurat"><i class="fas fa-first-aid"></i><span class="checkbox-text">Dana Darurat</span></label></div>
                  <!-- FIX: Mengganti checkbox dan label dengan button -->
                  <div class="category-item category-item-lainnya">
                      <button type="button" id="openLainnyaModalBtn" class="category-button">
                          <i class="fas fa-ellipsis-h"></i>
                          <span class="checkbox-text">Lainnya</span>
                      </button>
                  </div>
                </div>
              </div>
              <button type="button" id="saveBudgetButton" class="button-hitung">Hitung & Simpan Anggaran</button>
            </form>
          </main>
        </section>

        <section class="budgeting-content-cek" id="cek">
          <main class="main-hasil">
            <h1 class="label-budgeting">Hasil Perhitungan</h1>
            <div class="cek-container">
              <div class="results-grid" id="cekWrapper">
                <div class="hasil-box" id="uang-saku-display">
                  <h3 class="hasil-title">Uang Saku Anda</h3>
                  <div class="hasil-amount">Rp <span id="tampilJumlahUangSaku">0</span></div>
                  <p class="hasil-pesan">Silakan masukkan jumlah</p>
                </div>
                <div class="hasil-box" id="uang-makan-display">
                  <h3 class="hasil-title">Budget Makan Sebulan</h3>
                  <div class="hasil-amount">Rp <span id="tampilJumlahMakan">0</span></div>
                  <p class="hasil-pesan">Tidak dialokasikan.</p>
                </div>
                <div class="hasil-box" id="uang-tempat-tinggal-display">
                  <h3 class="hasil-title">Tempat Tinggal</h3>
                  <div class="hasil-amount">Rp <span id="tampilJumlahTempatTinggal">0</span></div>
                  <p class="hasil-pesan">Tidak dialokasikan.</p>
                </div>
                <div class="hasil-box" id="uang-internet-display">
                  <h3 class="hasil-title">Budget Internet</h3>
                  <div class="hasil-amount">Rp <span id="tampilJumlahInternet">0</span></div>
                  <p class="hasil-pesan">Tidak dialokasikan.</p>
                </div>
                <div class="hasil-box" id="uang-hiburan-display">
                  <h3 class="hasil-title">Hiburan Perbulan</h3>
                  <div class="hasil-amount">Rp <span id="tampilJumlahHiburan">0</span></div>
                  <p class="hasil-pesan">Tidak dialokasikan.</p>
                </div>
                <div class="hasil-box" id="dana-darurat-display">
                  <h3 class="hasil-title">Dana Darurat Perbulan</h3>
                  <div class="hasil-amount">Rp <span id="tampilJumlahDanaDarurat">0</span></div>
                  <p class="hasil-pesan">Tidak dialokasikan.</p>
                </div>
              </div>
            </div>
          </main>
        </section>
      </div>

      <div id="customCategoryModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h3>Tambah Kebutuhan Lainnya</h3>
          <form id="customCategoryForm">
            <div class="form-group"><input type="text" id="customCategoryName" placeholder="Nama Kebutuhan" required /></div>
            <div class="form-group"><input type="number" id="customCategoryAmount" placeholder="Nominal (Rp)" required /></div>
            <div class="modal-buttons">
              <button type="submit" id="addCustomCategory">Tambahkan</button>
              <button type="button" id="confirmCategories">Selesai</button>
            </div>
          </form>
          <hr>
          <div id="customCategoriesList"></div>
        </div>
      </div>
    </div>
    
    <script type="module" src="{{ asset('js/firebase-init.js') }}"></script>
    <script type="module" src="{{ asset('js/authGuard.js') }}"></script>
    <script type="module" src="{{ asset('js/budgeting.js') }}"></script>
  </body>
</html>
