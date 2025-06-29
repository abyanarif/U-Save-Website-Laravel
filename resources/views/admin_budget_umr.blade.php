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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tableBody = document.getElementById('umr-table-body');
            const addForm = document.getElementById('addForm');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const API_ENDPOINT = '/admin/api/budget/umr';

            const postData = async (url, method, body) => {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(body)
                });
                if (!response.ok) {
                    const err = await response.json();
                    throw new Error(err.message || 'Terjadi kesalahan.');
                }
                return await response.json();
            };

            const renderTable = async () => {
                try {
                    const response = await fetch(API_ENDPOINT);
                    const data = await response.json();
                    tableBody.innerHTML = '';
                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>';
                        return;
                    }
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.id}</td>
                            <td><input type="text" class="form-control form-control-sm" value="${item.nama_kota}" data-field="nama_kota"></td>
                            <td><input type="text" class="form-control form-control-sm" value="${item.provinsi || ''}" data-field="provinsi"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${item.umr}" data-field="umr"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${item.harga_makan_avg || ''}" data-field="harga_makan_avg"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${item.harga_kos_avg || ''}" data-field="harga_kos_avg"></td>
                            <td><input type="number" class="form-control form-control-sm" value="${item.harga_transport_avg || ''}" data-field="harga_transport_avg"></td>
                            <td>
                                <button class="btn btn-sm btn-success btn-update" data-id="${item.id}" title="Simpan"><i class="bi bi-check-lg"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}" title="Hapus"><i class="bi bi-trash"></i></button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } catch (error) {
                    tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Gagal memuat data: ${error.message}</td></tr>`;
                }
            };
            
            tableBody.addEventListener('click', async (e) => {
                const button = e.target.closest('button');
                if (!button) return;
                const id = button.dataset.id;
                
                if (button.classList.contains('btn-update')) {
                    const row = button.closest('tr');
                    const inputs = row.querySelectorAll('input');
                    let body = { id };
                    inputs.forEach(input => body[input.dataset.field] = input.value);
                    try {
                        await postData(`${API_ENDPOINT}/${id}`, 'PUT', body);
                        alert('Data kota berhasil diperbarui!');
                    } catch(e) { alert(`Error: ${e.message}`); }
                }

                if (button.classList.contains('btn-delete')) {
                    if (confirm('Apakah Anda yakin?')) {
                        try {
                           await postData(`${API_ENDPOINT}/${id}`, 'DELETE', {});
                           alert('Data kota berhasil dihapus!');
                           renderTable();
                        } catch(e) { alert(`Error: ${e.message}`); }
                    }
                }
            });

            addForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(addForm);
                const body = Object.fromEntries(formData.entries());
                try {
                    await postData(API_ENDPOINT, 'POST', body);
                    alert('Kota baru berhasil ditambahkan!');
                    addForm.reset();
                    renderTable();
                } catch(e) { alert(`Error: ${e.message}`); }
            });

            renderTable();
        });
    </script>
</body>
</html>
