<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Budgeting - U-Save</title>
    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <!-- FIX: Menggunakan helper asset() untuk path CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin_budgeting.css') }}" />
    <style>
      .sidebar {
        background-color: #f8f9fa;
        height: 100vh;
        position: sticky;
        top: 0;
      }
      .main-content-wrapper {
        height: 100vh;
        overflow-y: auto;
      }
      .main-content {
        padding: 2rem;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 sidebar p-0" id="sidebar">
          <div class="d-flex flex-column flex-shrink-0 p-3 h-100">
            <a
              href="/admin/dashboard"
              class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"
            >
              <span class="fs-4">
                <!-- FIX: Menggunakan helper asset() untuk path gambar -->
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo" />U-Save
              </span>
            </a>
            <hr />
            <ul class="nav nav-pills flex-column mb-auto">
              <li class="nav-item">
                <a href="/admin/dashboard" class="nav-link link-dark">
                  <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/articles" class="nav-link link-dark">
                  <i class="bi bi-book me-2"></i> Literasi Keuangan
                </a>
              </li>
              <li>
                <a
                  href="/admin/budget"
                  class="nav-link active"
                  aria-current="page"
                >
                  <i class="bi bi-calculator me-2"></i> Budgeting
                </a>
              </li>
            </ul>
            <hr />
            <div class="dropup mt-auto">
              <a
                href="#"
                class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <!-- FIX: Menggunakan helper asset() untuk path gambar -->
                <img
                  src="{{ asset('img/photo-profile.jpg') }}"
                  alt=""
                  width="32"
                  height="32"
                  class="rounded-circle me-2"
                />
                <strong>Admin</strong>
              </a>
              <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                <li><a class="dropdown-item" href="#">Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 p-0 main-content-wrapper">
            <main class="main-content" id="mainContent">
              <div class="d-flex align-items-center mb-4">
                <a href="/admin/budget" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
                <h2 class="mb-0" id="pageTitle">Manajemen Budgeting</h2>
              </div>
    
              <div id="dynamicContentContainer">
                <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
              </div>
            </main>
        </div>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const type = urlParams.get('type');
            
            const pageTitleEl = document.getElementById('pageTitle');
            const contentContainerEl = document.getElementById('dynamicContentContainer');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Fungsi untuk mengambil data dari API
            const fetchData = async (endpoint) => {
                try {
                    // FIX: Memperbaiki path API
                    const response = await fetch(`/admin/api/budget/${endpoint}`);
                    if (!response.ok) {
                        throw new Error(`Gagal memuat data: ${response.statusText}`);
                    }
                    return await response.json();
                } catch (error) {
                    contentContainerEl.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
                    console.error(error);
                }
            };
            
            // Fungsi untuk mengirim data (Create, Update, Delete)
            const postData = async (endpoint, method, body) => {
                 try {
                    // FIX: Memperbaiki path API
                    const response = await fetch(`/admin/api/budget/${endpoint}`, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(body)
                    });
                    if (!response.ok) {
                        const err = await response.json();
                        throw new Error(err.message || 'Terjadi kesalahan pada server.');
                    }
                    return await response.json();
                } catch (error) {
                    alert(`Error: ${error.message}`);
                    return null;
                }
            };

            // Render konten berdasarkan tipe
            switch (type) {
                case 'kategori':
                    pageTitleEl.textContent = 'Manajemen Kategori Budget';
                    renderKategori();
                    break;
                case 'alokasi':
                    pageTitleEl.textContent = 'Manajemen Aturan Alokasi';
                    renderAlokasi();
                    break;
                case 'umr':
                    pageTitleEl.textContent = 'Manajemen UMR Kota';
                    renderUmr();
                    break;
                default:
                    pageTitleEl.textContent = 'Manajemen Budgeting';
                    contentContainerEl.innerHTML = `<div class="alert alert-warning">Tipe tidak valid. Silakan pilih dari halaman sebelumnya.</div>`;
            }

            // --- FUNGSI-FUNGSI RENDER (tidak ada perubahan di sini) ---
            async function renderKategori() {
                const data = await fetchData('categories');
                if (!data) return;
                // ... (sisa logika renderKategori)
            }
            
            async function renderAlokasi() {
                const data = await fetchData('allocations');
                if (!data) return;
                // ... (sisa logika renderAlokasi)
            }
            
             async function renderUmr() {
                const data = await fetchData('umr');
                if (!data) return;
                // ... (sisa logika renderUmr)
            }
            
            // --- EVENT LISTENERS (tidak ada perubahan di sini) ---
            contentContainerEl.addEventListener('click', async (e) => {
                // ...
            });

            contentContainerEl.addEventListener('submit', async (e) => {
                // ...
            });
        });
    </script>
  </body>
</html>
