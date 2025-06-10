<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Budgeting - U-Save</title>
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
    <link rel="stylesheet" href="css/admin_budgeting.css" />
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 sidebar p-0" id="sidebar">
          <div class="d-flex flex-column flex-shrink-0 p-3 h-100">
            <a
              href="/"
              class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"
            >
              <span class="fs-4">
                <img
                  src="img/logo.png"
                  alt="Logo"
                  class="logo"
                />U-Save
              </span>
            </a>
            <hr />
            <ul class="nav nav-pills flex-column mb-auto">
              <li class="nav-item">
                <a href="admin_dashboard.html" class="nav-link link-dark">
                  <i class="bi bi-speedometer2 me-2"></i>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="admin_literasi_keuangan.html"
                  class="nav-link link-dark"
                >
                  <i class="bi bi-book me-2"></i>
                  Literasi Keuangan
                </a>
              </li>
              <li>
                <a
                  href="admin_budgeting.html"
                  class="nav-link active"
                  aria-current="page"
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
                  src="img/photo-profile.jpg"
                  alt=""
                  width="32"
                  height="32"
                  class="rounded-circle me-2"
                />
                <strong>Admin</strong>
              </a>
              <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 main-content" id="mainContent">
          <h2 class="mb-4">Manajemen Budgeting</h2>

          <!-- Budgeting Cards -->
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <a
                href="edit_budgeting.html?type=kategori"
                class="card budgeting-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i class="bi bi-tags budgeting-icon text-primary"></i>
                  <h5 class="budgeting-title">Kategori Budget</h5>
                  <p class="budgeting-desc">Kelola kategori pengeluaran</p>
                </div>
              </a>
            </div>
            <div class="col-md-6 mb-3">
              <a
                href="edit_budgeting.html?type=alokasi"
                class="card budgeting-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i class="bi bi-percent budgeting-icon text-warning"></i>
                  <h5 class="budgeting-title">Alokasi Budget</h5>
                  <p class="budgeting-desc">Kelola aturan alokasi</p>
                </div>
              </a>
            </div>
            <div class="col-md-12 mb-3">
              <a
                href="edit_budgeting.html?type=umr"
                class="card budgeting-card h-100 text-decoration-none"
              >
                <div class="card-body text-center">
                  <i class="bi bi-geo-alt budgeting-icon text-info"></i>
                  <h5 class="budgeting-title">UMR Kota</h5>
                  <p class="budgeting-desc">Kelola UMR per kota</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="module" src="firebase-init.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
