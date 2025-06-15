<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kategori Budget</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <style>
        .sidebar { background-color: #f8f9fa; height: 100vh; position: sticky; top: 0; }
        .main-content-wrapper { height: 100vh; overflow-y: auto; }
        .main-content { padding: 2rem; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (Anda bisa menyertakan sidebar di sini jika ini adalah file mandiri) -->
            <div class="col-lg-2 sidebar p-0">
                @include('partials.admin_sidebar', ['active' => 'budgeting']) {{-- Contoh jika Anda punya partial sidebar --}}
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 p-0 main-content-wrapper">
                <main class="main-content" id="mainContent">
                    <div class="d-flex align-items-center mb-4">
                        <a href="{{ route('admin.budgeting.index') }}" class="btn btn-outline-secondary me-3" title="Kembali"><i class="bi bi-arrow-left"></i></a>
                        <h2 class="mb-0">Manajemen Kategori Budget</h2>
                    </div>
                    <div id="kategoriContent">
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
            const contentContainerEl = document.getElementById('kategoriContent');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const API_ENDPOINT = '/admin/api/budget/categories';

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

            const renderKategori = async () => {
                try {
                    const response = await fetch(API_ENDPOINT);
                    const data = await response.json();

                    let tableRows = data.map(item => `
                        <tr>
                            <td>${item.id}</td>
                            <td><input type="text" class="form-control form-control-sm" value="${item.name}" data-id="${item.id}" data-field="name"></td>
                            <td><input type="text" class="form-control form-control-sm" value="${item.display_text}" data-id="${item.id}" data-field="display_text"></td>
                            <td><input type="text" class="form-control form-control-sm" value="${item.icon_class || ''}" data-id="${item.id}" data-field="icon_class"></td>
                            <td>
                                <button class="btn btn-sm btn-success btn-update" data-id="${item.id}" title="Simpan"><i class="bi bi-check-lg"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}" title="Hapus"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>`).join('');

                    contentContainerEl.innerHTML = `
                        <div class="card"><div class="card-header">Daftar Kategori</div>
                            <div class="card-body"><div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead><tr><th>ID</th><th>Nama Sistem</th><th>Teks Tampilan</th><th>Kelas Ikon (e.g., bi bi-tags)</th><th>Aksi</th></tr></thead>
                                    <tbody>${tableRows}</tbody>
                                </table>
                            </div></div>
                            <div class="card-footer">
                                <h5>Tambah Kategori Baru</h5>
                                <form id="addForm" class="row g-3 align-items-end">
                                    <div class="col-md-3"><label class="form-label">Nama Sistem</label><input type="text" class="form-control" name="name" placeholder="e.g., makan" required></div>
                                    <div class="col-md-4"><label class="form-label">Teks Tampilan</label><input type="text" class="form-control" name="display_text" placeholder="e.g., Makan Sehari-hari" required></div>
                                    <div class="col-md-4"><label class="form-label">Kelas Ikon</label><input type="text" class="form-control" name="icon_class" placeholder="e.g., bi bi-utensils"></div>
                                    <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Tambah</button></div>
                                </form>
                            </div></div>`;
                } catch (error) {
                    contentContainerEl.innerHTML = `<div class="alert alert-danger">Gagal memuat data: ${error.message}</div>`;
                }
            };
            
            contentContainerEl.addEventListener('click', async (e) => {
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
                        alert('Kategori berhasil diperbarui!');
                    } catch(e) { /* error ditangani di postData */ }
                }

                if (button.classList.contains('btn-delete')) {
                    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                         try {
                           await postData(`${API_ENDPOINT}/${id}`, 'DELETE', {});
                           alert('Kategori berhasil dihapus!');
                           renderKategori();
                        } catch(e) { /* error ditangani di postData */ }
                    }
                }
            });

            contentContainerEl.addEventListener('submit', async (e) => {
                if (e.target.id === 'addForm') {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    const body = Object.fromEntries(formData.entries());
                    try {
                        await postData(API_ENDPOINT, 'POST', body);
                        alert('Kategori baru berhasil ditambahkan!');
                        e.target.reset();
                        renderKategori();
                    } catch(e) { /* error ditangani di postData */ }
                }
            });

            renderKategori();
        });
    </script>
</body>
</html>
