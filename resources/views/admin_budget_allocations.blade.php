<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Aturan Alokasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .sidebar { background-color: #f8f9fa; height: 100vh; position: sticky; top: 0; }
        .main-content-wrapper { height: 100vh; overflow-y: auto; }
        .main-content { padding: 2rem; }
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
                    {{-- Profile Dropdown --}}
                </div>
            </div>
            <!-- Main Content -->
            <div class="col-lg-10 p-0 main-content-wrapper">
                <main class="main-content" id="mainContent">
                    <div class="d-flex align-items-center mb-4">
                        <a href="{{ route('admin.budgeting.index') }}" class="btn btn-outline-secondary me-3" title="Kembali"><i class="bi bi-arrow-left"></i></a>
                        <h2 class="mb-0">Manajemen Aturan Alokasi</h2>
                    </div>
                    <div id="alokasiContent">
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
            const contentContainerEl = document.getElementById('alokasiContent');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const API_ENDPOINT = '/admin/api/budget/allocations';

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

            const renderAlokasi = async () => {
                try {
                    const response = await fetch(API_ENDPOINT);
                    const data = await response.json();
                    
                    let tableRows = data.map(item => `
                        <tr>
                            <td>${item.id}</td>
                            <td><strong>${item.category_name}</strong></td>
                            <td><input type="number" step="0.01" class="form-control form-control-sm" value="${item.weight}" data-id="${item.id}" data-field="weight"></td>
                            <td>
                                <button class="btn btn-sm btn-success btn-update" data-id="${item.id}" title="Simpan"><i class="bi bi-check-lg"></i></button>
                            </td>
                        </tr>`).join('');
                        
                    contentContainerEl.innerHTML = `
                         <div class="card"><div class="card-header">Bobot Alokasi per Kategori</div>
                            <div class="card-body">
                                <p class="text-muted">Bobot ini digunakan untuk menghitung persentase alokasi secara otomatis. Total bobot dari semua kategori yang dipilih oleh pengguna akan dianggap sebagai 100%.</p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead><tr><th>ID</th><th>Nama Kategori</th><th>Bobot (Weight)</th><th>Aksi</th></tr></thead>
                                        <tbody id="table-body">${tableRows}</tbody>
                                    </table>
                                </div>
                            </div></div>`;
                } catch(error) {
                    contentContainerEl.innerHTML = `<div class="alert alert-danger">Gagal memuat data: ${error.message}</div>`;
                }
            };
            
            contentContainerEl.addEventListener('click', async (e) => {
                const button = e.target.closest('button');
                if (!button || !button.classList.contains('btn-update')) return;
                
                const id = button.dataset.id;
                const row = button.closest('tr');
                const input = row.querySelector("input[data-field='weight']");
                let body = { id, weight: input.value };
                
                try {
                    await postData(`${API_ENDPOINT}/${id}`, 'PUT', body);
                    alert('Aturan alokasi berhasil diperbarui!');
                } catch(e) { alert(`Error: ${e.message}`); }
            });

            renderAlokasi();
        });
    </script>
</body>
</html>
