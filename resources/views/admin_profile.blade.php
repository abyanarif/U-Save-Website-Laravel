<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - U-Save</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <style>
        .sidebar { background-color: #f8f9fa; height: 100vh; position: sticky; top: 0; }
        .main-content-wrapper { height: 100vh; overflow-y: auto; }
        .main-content { padding: 2rem; }
        .profile-card { max-width: 700px; margin: auto; }
        .profile-icon { font-size: 1.2rem; width: 30px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar p-0">
                {{-- Anda bisa menggunakan @include untuk memuat sidebar parsial --}}
                <div class="d-flex flex-column flex-shrink-0 p-3 h-100">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                        <span class="fs-4"><img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo" style="width:32px; margin-right: 8px;"/>U-Save</span>
                    </a>
                    <hr />
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link link-dark"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li class="nav-item"><a href="{{ route('admin.articles.index') }}" class="nav-link link-dark"><i class="bi bi-book me-2"></i>Literasi Keuangan</a></li>
                        <li><a href="{{ route('admin.budgeting.index') }}" class="nav-link link-dark"><i class="bi bi-calculator me-2"></i>Budgeting</a></li>
                    </ul>
                    <hr />
                    <div class="dropup mt-auto">
                      <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('img/photo-profile.jpg') }}" alt="" width="32" height="32" class="rounded-circle me-2"/>
                        <strong id="admin-name-sidebar">Admin</strong>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                      </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 p-0 main-content-wrapper">
                <main class="main-content">
                    <h2 class="mb-4">Profil Admin</h2>
                    <div class="card profile-card shadow-sm">
                        <div class="card-body p-4" id="profile-content">
                            <div class="text-center p-5">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const profileContent = document.getElementById('profile-content');
            const adminNameSidebar = document.getElementById('admin-name-sidebar');
            
            try {
                const response = await fetch('/admin/api/profile');
                if (!response.ok) {
                    throw new Error(`Gagal memuat profil admin. Status: ${response.status}`);
                }
                const admin = await response.json();

                if(adminNameSidebar) adminNameSidebar.textContent = admin.username;

                profileContent.innerHTML = `
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('img/photo-profile.jpg') }}" alt="Admin" width="80" height="80" class="rounded-circle me-3">
                        <div>
                            <h3 class="mb-0">${admin.username}</h3>
                            <span class="badge bg-primary">${admin.role}</span>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-person-badge-fill profile-icon text-muted me-3"></i>
                            <div>
                                <small class="text-muted">Username</small>
                                <div>${admin.username}</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-envelope-fill profile-icon text-muted me-3"></i>
                            <div>
                                <small class="text-muted">Email</small>
                                <div>${admin.email}</div>
                            </div>
                        </li>
                         <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-clock-history profile-icon text-muted me-3"></i>
                            <div>
                                <small class="text-muted">Akun Dibuat</small>
                                <div>${new Date(admin.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
                            </div>
                        </li>
                    </ul>
                `;

            } catch (error) {
                profileContent.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
                console.error(error);
            }
        });
    </script>
</body>
</html>
