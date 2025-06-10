<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - U-Save</title>
    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    />
    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css" />
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar Toggler Button (Mobile) -->
        <button
          class="btn btn-primary sidebar-toggler d-none position-fixed"
          id="sidebarToggle"
        >
          <i class="bi bi-list"></i>
        </button>

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
                <a href="#" class="nav-link active" aria-current="page">
                  <i class="bi bi-speedometer2 me-2"></i>
                  Dashboard
                </a>
              </li>
              <li>
                <a
                  href="admin_literasi_keuangan.html"
                  class="nav-link link-dark"
                >
                  <i class="bi bi-book me-2"></i>
                  Literasi Keuangan
                </a>
              </li>
              <li>
                <a href="admin_budgeting.html" class="nav-link link-dark">
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
          <h2 class="mb-4">Dashboard</h2>

          <!-- Stats Cards -->
          <div class="row mb-4">
            <div class="col-md-4 mb-3">
              <div class="card stat-card h-100">
                <div class="card-body">
                  <div class="stat-number">1,254</div>
                  <div class="stat-label">Total Pengguna</div>
                  <i class="bi bi-people-fill text-primary mt-2 stat-icon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="card stat-card h-100">
                <div class="card-body">
                  <div class="stat-number">843</div>
                  <div class="stat-label">Pengguna Aktif</div>
                  <i
                    class="bi bi-person-check-fill text-success mt-2 stat-icon"
                  ></i>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="card stat-card h-100">
                <div class="card-body">
                  <div class="stat-number">24</div>
                  <div class="stat-label">Universitas</div>
                  <i class="bi bi-building-fill text-info mt-2 stat-icon"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- Users Table -->
          <div class="card">
            <div
              class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center"
            >
              <span class="mb-2 mb-md-0">Data Pengguna</span>
              <div class="d-flex flex-column flex-md-row">
                <input
                  type="text"
                  class="form-control form-control-sm mb-2 mb-md-0 me-md-2 search-input"
                  placeholder="Search..."
                />
                <button class="btn btn-sm btn-primary filter-btn">
                  <i class="bi bi-funnel"></i> Filter
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover user-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>Password</th>
                      <th>Universitas</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>john_doe</td>
                      <td>john@example.com</td>
                      <td>••••••••</td>
                      <td>Universitas Indonesia</td>
                      <td>
                        <div class="action-buttons">
                          <button
                            class="btn btn-sm btn-outline-primary me-1 view-btn"
                          >
                            <i class="bi bi-eye"></i>
                          </button>
                          <button
                            class="btn btn-sm btn-outline-warning me-1 edit-btn"
                          >
                            <i class="bi bi-pencil"></i>
                          </button>
                          <button
                            class="btn btn-sm btn-outline-danger delete-btn"
                          >
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <!-- Additional rows would go here -->
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <nav aria-label="Page navigation">
                <ul
                  class="pagination justify-content-center mt-3 flex-wrap pagination-container"
                >
                  <li class="page-item disabled">
                    <a
                      class="page-link"
                      href="#"
                      tabindex="-1"
                      aria-disabled="true"
                      >Previous</a
                    >
                  </li>
                  <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    ></script>

    <!-- Custom JavaScript -->
    <script src="script.js"></script>
  </body>
</html>
