<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - U-Save</title>
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
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
    <style>
        .sidebar { background-color: #f8f9fa; height: 100vh; position: sticky; top: 0; }
        .main-content-wrapper { height: 100vh; overflow-y: auto; }
        .main-content { padding: 2rem; }
        .stat-card { text-align: center; }
        .stat-icon { font-size: 2.5rem; }
        .action-buttons .btn { margin: 0 2px; }
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
                <a href="/admin/dashboard" class="nav-link active" aria-current="page">
                  <i class="bi bi-speedometer2 me-2"></i>
                  Dashboard
                </a>
              </li>
              <li>
                <a
                  href="/admin/articles"
                  class="nav-link link-dark"
                >
                  <i class="bi bi-book me-2"></i>
                  Literasi Keuangan
                </a>
              </li>
              <li>
                <a href="/admin/budget" class="nav-link link-dark">
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
                <!-- FIX: Menambahkan kembali tautan Profile -->
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <main class="col-lg-10 main-content-wrapper p-0">
          <div class="main-content" id="mainContent">
            <h2 class="mb-4">Dashboard</h2>
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                  <div class="card stat-card h-100">
                    <div class="card-body">
                      <div class="stat-number" id="totalUsersStat">0</div>
                      <div class="stat-label">Total Pengguna</div>
                      <i class="bi bi-people-fill text-primary mt-2 stat-icon"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="card stat-card h-100">
                    <div class="card-body">
                      <div class="stat-number" id="universitiesStat">0</div>
                      <div class="stat-label">Universitas</div>
                      <i class="bi bi-building-fill text-info mt-2 stat-icon"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="card stat-card h-100">
                    <div class="card-body">
                      <div class="stat-number" id="articlesStat">0</div>
                      <div class="stat-label">Artikel</div>
                      <i class="bi bi-book-half text-success mt-2 stat-icon"></i>
                    </div>
                  </div>
                </div>
            </div>
            <!-- Users Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <span>Data Pengguna</span>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover user-table">
                      <thead>
                        <tr>
                          <th>#</th><th>Username</th><th>Email</th><th>Universitas</th><th>Tanggal Bergabung</th><th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody id="user-table-body">
                        <tr><td colspan="6" class="text-center">Memuat data...</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', async () => {
          const userTableBody = document.getElementById('user-table-body');
          const totalUsersStat = document.getElementById('totalUsersStat');
          const universitiesStat = document.getElementById('universitiesStat');
          const articlesStat = document.getElementById('articlesStat');

          try {
              const response = await fetch("/admin/api/users");
              if (!response.ok) {
                  throw new Error(`Gagal memuat data pengguna. Status: ${response.status}`);
              }
              const data = await response.json();

              totalUsersStat.textContent = data.stats.total_users;
              universitiesStat.textContent = data.stats.total_universities;
              articlesStat.textContent = data.stats.total_articles;

              userTableBody.innerHTML = '';
              if(data.users.length > 0) {
                  data.users.forEach((user, index) => {
                      const row = `
                          <tr>
                              <td>${index + 1}</td>
                              <td>${user.username}</td>
                              <td>${user.email}</td>
                              <td>${user.university ? user.university.name : 'N/A'}</td>
                              <td>${new Date(user.created_at).toLocaleDateString('id-ID')}</td>
                              <td>
                                  <div class="action-buttons">
                                      <button class="btn btn-sm btn-outline-warning me-1 edit-btn" title="Edit"><i class="bi bi-pencil"></i></button>
                                      <button class="btn btn-sm btn-outline-danger delete-btn" title="Delete"><i class="bi bi-trash"></i></button>
                                  </div>
                              </td>
                          </tr>
                      `;
                      userTableBody.innerHTML += row;
                  });
              } else {
                  userTableBody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada data pengguna.</td></tr>';
              }
          } catch (error) {
              console.error("Error:", error);
              userTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data: ${error.message}</td></tr>`;
          }
      });
    </script>
  </body>
</html>
