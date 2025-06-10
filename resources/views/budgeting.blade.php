<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Budgeting U-Save</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="{{ asset('css/navbar_style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/budgeting.css') }}" />

    <script>
        // Menyematkan data opsi kota yang dikirim dari BudgetController
        window.citiesForDropdown = @json($citiesForDropdown ?? []);
    </script>
</head>
<body>
    <div class="fContainer-budgeting">
        <nav class="wrapper" id="budgeting-nav">
            <div class="brand">
                <img src="{{ asset('img/logo.png') }}" alt="logo U-Save" class="logo" />
                <div class="namaBrand">U-Save</div>
            </div>
            <div class="menu-icon" id="menu-icon" aria-label="Toggle menu">
                <i class="fa-solid fa-list"></i>
            </div>
            <ul class="navigation hidden" id="menu-list">
                <li><a href="{{ url('/home') }}">Home</a></li>
                <li><a href="{{ url('/literasi-keuangan') }}">Literasi Keuangan</a></li>
                <li>
                    <a href="{{ url('/budgeting') }}" class="active" id="activeText">Budgeting</a>
                </li>
                <li><a href="{{ url('/profile') }}">Profile</a></li>
            </ul>
        </nav>
        <div class="budgeting-content-sections">
            <section class="budgeting-content1">
                <main class="budgeting-main">
                    <div class="budgeting-judul">
                        <h1 class="judul-budgeting" id="judul1-budgeting">U-SAVE</h1>
                        <p class="judul-budgeting" id="judul2-budgeting">
                            University Save
                        </p>
                        <div class="garis-bawah-budgeting"></div>
                        <h2>Budgeting</h2>
                        <h2>Otomatis</h2>
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
                    <form id="budgetingForm" class="budgeting-form">
                        <input type="text" placeholder="Uang Saku Bulanan" class="rupiah-input" id="uangSakuInput" required inputmode="numeric" />

                        <div class="form-group">
                            <label for="asalKota">Asal Kota:</label>
                            <select id="asalKota" name="asalKota" class="scrollable" required>
                                <option value="">-- Pilih Kota --</option>
                                <!-- Opsi kota akan diisi oleh JavaScript menggunakan window.citiesForDropdown -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label id="budgetCategoryLabel">Alokasikan untuk (pilih minimal satu):</label>
                            <div class="category-item-wrapper" role="group" aria-labelledby="budgetCategoryLabel">
                                <div class="budget-categories">
                                    <div class="category-item">
                                        <input type="checkbox" id="kos" name="budgetCategory" value="kos" />
                                        <label for="kos">
                                            <i class="fas fa-home" aria-hidden="true"></i>
                                            <span class="checkbox-text">Kos/Akomodasi</span>
                                        </label>
                                    </div>
                                    <div class="category-item">
                                        <input type="checkbox" id="makan" name="budgetCategory" value="makan" />
                                        <label for="makan">
                                            <i class="fas fa-utensils" aria-hidden="true"></i>
                                            <span class="checkbox-text">Makan</span>
                                        </label>
                                    </div>
                                    <div class="category-item">
                                        <input type="checkbox" id="hiburan" name="budgetCategory" value="hiburan" />
                                        <label for="hiburan">
                                            <i class="fas fa-gamepad" aria-hidden="true"></i>
                                            <span class="checkbox-text">Hiburan</span>
                                        </label>
                                    </div>
                                    <div class="category-item">
                                        <input type="checkbox" id="internet" name="budgetCategory" value="internet" />
                                        <label for="internet">
                                            <i class="fas fa-wifi" aria-hidden="true"></i>
                                            <span class="checkbox-text">Internet</span>
                                        </label>
                                    </div>
                                    <div class="category-item">
                                        <input type="checkbox" id="darurat" name="budgetCategory" value="darurat" />
                                        <label for="darurat">
                                            <i class="fas fa-first-aid" aria-hidden="true"></i>
                                            <span class="checkbox-text">Dana Darurat</span>
                                        </label>
                                    </div>
                                    <div class="category-item category-item-lainnya">
                                        <input type="checkbox" id="lainnya" name="budgetCategory" value="lainnya" />
                                        <label for="lainnya">
                                            <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                                            <span class="checkbox-text">Lainnya</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="customCategoryModal" class="modal" role="dialog" aria-labelledby="customCategoryModalTitle" aria-modal="true" style="display: none;">
                            <div class="modal-content">
                                <button type="button" class="close" aria-label="Tutup modal">&times;</button>
                                <h3 id="customCategoryModalTitle">Tambah Kebutuhan Lainnya</h3>
                                <form id="customCategoryForm" onsubmit="return false;">
                                    <div class="form-group">
                                        <label for="customCategoryName" class="sr-only">Nama Kebutuhan</label>
                                        <input type="text" id="customCategoryName" placeholder="Nama Kebutuhan" required />
                                    </div>
                                    <div class="form-group">
                                         <label for="customCategoryAmount" class="sr-only">Nominal (Rp)</label>
                                        <input type="text" id="customCategoryAmount" placeholder="Nominal (Rp)" required inputmode="numeric" class="rupiah-input"/>
                                    </div>
                                    <div class="modal-buttons">
                                        <button type="submit" id="addCustomCategory">Tambahkan</button>
                                        <button type="button" id="confirmCategories">Selesai & Tutup</button>
                                    </div>
                                </form>
                                <div id="customCategoriesList" aria-live="polite"></div>
                            </div>
                        </div>
                        
                        <button type="button" id="saveBudgetButton" class="button-hitung">
                            Hitung & Simpan Anggaran
                        </button>
                    </form>
                </main>
            </section>
            <section class="budgeting-content-cek" id="cek">
                <main class="main-hasil">
                    <h1 class="label-budgeting">Cek Anggaran Tersimpan</h1>
                    <div class="cek-container">
                        <div class="cek-wrapper" id="cekWrapper">
                            <div class="hasil-container" id="hasilUangSaku">
                                <div class="hasil-box" id="uang-saku-display">
                                    <h3 class="hasil-title">Uang Saku Anda</h3>
                                    <div class="hasil-amount">
                                        Rp <span id="tampilJumlahUangSaku">0</span>
                                    </div>
                                    <p class="hasil-pesan">Belum ada data.</p>
                                </div>
                                <div class="hasil-box" id="uang-makan-display">
                                    <h3 class="hasil-title">Budget Makan Sebulan</h3>
                                    <div class="hasil-amount">
                                        Rp <span id="tampilJumlahMakan">0</span>
                                    </div>
                                    <p class="hasil-pesan">Belum ada data.</p>
                                </div>
                                <div class="hasil-box" id="uang-tempat-tinggal-display">
                                    <h3 class="hasil-title">Tempat Tinggal</h3>
                                    <div class="hasil-amount">
                                        Rp <span id="tampilJumlahTempatTinggal">0</span>
                                    </div>
                                    <p class="hasil-pesan">Belum ada data.</p>
                                </div>
                            </div>
                            <div class="hasil-container">
                                <div class="hasil-box" id="uang-internet-display">
                                    <h3 class="hasil-title">Budget Internet</h3>
                                    <div class="hasil-amount">
                                        Rp <span id="tampilJumlahInternet">0</span>
                                    </div>
                                    <p class="hasil-pesan">Belum ada data.</p>
                                </div>
                                <div class="hasil-box" id="uang-hiburan-display">
                                    <h3 class="hasil-title">Hiburan Perbulan</h3>
                                    <div class="hasil-amount">
                                        Rp <span id="tampilJumlahHiburan">0</span>
                                    </div>
                                    <p class="hasil-pesan">Belum ada data.</p>
                                </div>
                                <div class="hasil-box" id="dana-darurat-display">
                                    <h3 class="hasil-title">Dana Darurat Perbulan</h3>
                                    <div class="hasil-amount">
                                        Rp <span id="tampilJumlahDanaDarurat">0</span>
                                    </div>
                                    <p class="hasil-pesan">Belum ada data.</p>
                                </div>
                            </div>
                            {{-- Kontainer untuk item kustom akan ditambahkan di sini oleh JavaScript --}}
                        </div>
                    </div>
                </main>
            </section>
        </div>
    </div>
    {{-- Script dipindahkan ke bawah sebelum closing body --}}
    <script type="module" src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script type="module" src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
    <script type="module" src="{{ asset('js/firebase-init.js') }}"></script> 
    <script type="module" src="{{ asset('js/authGuard.js') }}"></script>
    <script type="module" src="{{ asset('js/budgeting.js') }}"></script>
</body>
</html>
