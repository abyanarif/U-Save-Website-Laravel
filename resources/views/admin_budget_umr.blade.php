<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Data Kota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <style>
        .sidebar { background-color: #f8f9fa; height: 100vh; position: sticky; top: 0; }
        .main-content-wrapper { height: 100vh; overflow-y: auto; }
        .main-content { padding: 2rem; }
        .table-responsive { max-height: 50vh; }
        .table thead { position: sticky; top: 0; background: white; z-index: 1; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar p-0">
                {{-- Anda bisa menggunakan @include untuk memuat sidebar parsial --}}
                {{-- Contoh: @include('partials.admin_sidebar', ['active' => 'budgeting']) --}}
                 <div class="d-flex flex-column flex-shrink-0 p-3 h-100">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                        <span class="fs-4"><img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo" style="width:32px; margin-right: 8px;"/>U-Save</span>
                    </a>
                    <hr />
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link link-dark"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li class="nav-item"><a href="{{ route('admin.articles.index') }}" class="nav-link link-dark"><i class="bi bi-book me-2"></i>Literasi Keuangan</a></li>
                        <li><a href="{{ route('admin.budgeting.index') }}" class="nav-link active" aria-current="page"><i class="bi bi-calculator me-2"></i>Budgeting</a></li>
                    </ul>
                    <hr />
                    <div class="dropup mt-auto">
                      <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('img/photo-profile.jpg') }}" alt="" width="32" height="32" class="rounded-circle me-2"/>
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
                <main class="main-content">
                    <div class="d-flex align-items-center mb-4">
                        <a href="{{ route('admin.budgeting.index') }}" class="btn btn-outline-secondary me-3" title="Kembali"><i class="bi bi-arrow-left"></i></a>
                        <h2 class="mb-0">Manajemen Data Kota & Biaya Hidup</h2>
                    </div>

                    <div class="card">
                        <div class="card-header">Daftar Kota</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Kota</th>
                                            <th>Provinsi</th>
                                            <th>UMR (Rp)</th>
                                            <th>Avg. Makan</th>
                                            <th>Avg. Kos</th>
                                            <th>Avg. Transport</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="umr-table-body">
                                        <tr><td colspan="8" class="text-center">Memuat data...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <h5>Tambah Kota Baru</h5>
                            <form id="addForm" class="row g-3">
                                <div class="col-md-4"><input type="text" class="form-control" name="nama_kota" placeholder="Nama Kota" required></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="provinsi" placeholder="Provinsi" required></div>
                                <div class="col-md-4"><input type="number" class="form-control" name="umr" placeholder="UMR" required></div>
                                <div class="col-md-3"><input type="number" class="form-control" name="harga_makan_avg" placeholder="Avg. Makan"></div>
                                <div class="col-md-3"><input type="number" class="form-control" name="harga_kos_avg" placeholder="Avg. Kos"></div>
                                <div class="col-md-3"><input type="number" class="form-control" name="harga_transport_avg" placeholder="Avg. Transport"></div>
                                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Tambah</button></div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin_budget_umr.js"></script>
</body>
</html>
