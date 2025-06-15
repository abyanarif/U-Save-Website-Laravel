<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Literasi Keuangan - U-Save</title>
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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin_literasi_keuangan.css') }}" />
    <style>
        .sidebar { 
            background-color: #f8f9fa; 
            height: 100vh; 
            position: sticky; 
            top: 0; 
        }
        /* FIX: Membuat area konten utama menjadi scrollable */
        .main-content-wrapper {
            height: 100vh;
            overflow-y: auto;
        }
        .main-content { 
            padding: 2rem; 
        }
        .literacy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.2s ease-in-out;
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
              href="{{ route('admin.dashboard') }}"
              class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"
            >
              <span class="fs-4">
                <img
                  src="{{ asset('img/logo.png') }}"
                  alt="Logo"
                  class="logo"
                />U-Save
              </span>
            </a>
            <hr />
            <ul class="nav nav-pills flex-column mb-auto">
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link link-dark">
                  <i class="bi bi-speedometer2 me-2"></i>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="{{ route('admin.articles.index') }}"
                  class="nav-link active"
                  aria-current="page"
                >
                  <i class="bi bi-book me-2"></i>
                  Literasi Keuangan
                </a>
              </li>
              <li>
                <a
                  href="{{ route('admin.budgeting.index') }}"
                  class="nav-link link-dark"
                >
                  <i class="bi bi-calculator me-2"></i>
                  Budgeting
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

        <!-- FIX: Wrapper untuk Main Content -->
        <div class="col-lg-10 p-0 main-content-wrapper">
            <main class="main-content" id="mainContent">
                <h2 class="mb-4">Literasi Keuangan</h2>
                <p class="text-muted">Pilih artikel di bawah ini untuk dikelola kontennya.</p>
                
                <!-- Financial Literacy Buttons -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <a
                            href="{{ route('admin.articles.edit', ['section_id' => 'pemahaman-dasar']) }}"
                            class="card literacy-card h-100 text-decoration-none"
                        >
                            <div class="card-body text-center">
                                <i class="bi bi-journal-bookmark literacy-icon text-primary"></i>
                                <h5 class="literacy-title">Pemahaman Dasar</h5>
                                <p class="literacy-desc">
                                    Kelola konten literasi keuangan dasar
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a
                            href="{{ route('admin.articles.edit', ['section_id' => 'tabungan']) }}"
                            class="card literacy-card h-100 text-decoration-none"
                        >
                            <div class="card-body text-center">
                                <i class="bi bi-piggy-bank literacy-icon text-success"></i>
                                <h5 class="literacy-title">Tabungan & Pinjaman</h5>
                                <p class="literacy-desc">
                                    Kelola konten tentang tabungan dan pinjaman
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a
                            href="{{ route('admin.articles.edit', ['section_id' => 'investasi']) }}"
                            class="card literacy-card h-100 text-decoration-none"
                        >
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up-arrow literacy-icon text-warning"></i>
                                <h5 class="literacy-title">Investasi</h5>
                                <p class="literacy-desc">Kelola konten tentang investasi</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a
                            href="{{ route('admin.articles.edit', ['section_id' => 'asuransi']) }}"
                            class="card literacy-card h-100 text-decoration-none"
                        >
                            <div class="card-body text-center">
                                <i class="bi bi-shield-check literacy-icon text-info"></i>
                                <h5 class="literacy-title">Asuransi</h5>
                                <p class="literacy-desc">Kelola konten tentang asuransi</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a
                            href="{{ route('admin.articles.edit', ['section_id' => 'perencanaan']) }}"
                            class="card literacy-card h-100 text-decoration-none"
                        >
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-check literacy-icon text-danger"></i>
                                <h5 class="literacy-title">Perencanaan Jangka Panjang</h5>
                                <p class="literacy-desc">
                                    Kelola konten perencanaan keuangan
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </main>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
