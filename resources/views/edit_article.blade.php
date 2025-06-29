<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Artikel Literasi Keuangan</title>
    <!-- Bootstrap CSS (Opsional, untuk tampilan yang lebih baik) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
    <!-- Muat TinyMCE dari CDN -->
    <script src="https://cdn.tiny.cloud/1/jea0x5zq1xgq01p1iwsrad0tfmou8hl5hjj2ve8ojokkd0ld/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary me-3" title="Kembali"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h1 class="mb-0">Edit Artikel Literasi Keuangan</h1>
                <p id="article-section-title" class="text-muted fs-5 mb-0">Memuat...</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form id="editForm" style="display:none;">
                    <div class="mb-3">
                        <label for="articleTitle" class="form-label fw-bold">Judul Artikel</label>
                        <input type="text" class="form-control" id="articleTitle" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="articleContent" class="form-label fw-bold">Isi Artikel</label>
                        <textarea id="articleContent" name="content"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="articleImage" class="form-label fw-bold">URL Gambar (Opsional)</label>
                        <input type="text" class="form-control" id="articleImage" name="image_url" placeholder="Contoh: /img/nama-gambar.png">
                    </div>
                    
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <span id="statusMessage" class="ms-3"></span>
                </form>

                <div id="loadingIndicator">
                    <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        tinymce.init({
            selector: 'textarea#articleContent',
            plugins: 'lists link image table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
            height: 400,
        });

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('editForm');
            const statusMessage = document.getElementById('statusMessage');
            const sectionTitleEl = document.getElementById('article-section-title');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Ambil section_id dari URL
            const pathParts = window.location.pathname.split('/');
            const currentSectionId = pathParts[pathParts.indexOf('edit') - 1];
            
            if (currentSectionId) {
                sectionTitleEl.textContent = `Mengedit Bagian: ${currentSectionId}`;
            } else {
                sectionTitleEl.textContent = 'Error: Section ID tidak ditemukan di URL.';
                loadingIndicator.style.display = 'none';
                return;
            }

            // Fungsi untuk mengambil data awal
            const loadArticleData = async () => {
                try {
                    // Panggil URL API publik untuk mengambil data
                    const response = await fetch(`/api/articles/${currentSectionId}`);
                    if (!response.ok) {
                        throw new Error(`Gagal memuat artikel (Status: ${response.status})`);
                    }
                    const article = await response.json();
                    
                    document.getElementById('articleTitle').value = article.title;
                    document.getElementById('articleImage').value = article.image_url || '';
                    tinymce.get('articleContent').setContent(article.content);
                    
                    loadingIndicator.style.display = 'none';
                    form.style.display = 'block';
                } catch (error) {
                    loadingIndicator.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
                }
            };
            
            loadArticleData();

            // Event listener saat admin submit form
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                statusMessage.textContent = 'Menyimpan...';
                statusMessage.classList.remove('text-success', 'text-danger');

                const content = tinymce.get('articleContent').getContent();
                const formData = {
                    title: document.getElementById('articleTitle').value,
                    content: content,
                    image_url: document.getElementById('articleImage').value,
                    _method: 'PUT' // Menambahkan ini untuk Laravel
                };

                try {
                    // Menggunakan metode POST, tetapi Laravel akan menginterpretasikannya sebagai PUT
                    const response = await fetch(`/admin/api/articles/${currentSectionId}`, {
                        method: 'POST', 
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(formData)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Gagal menyimpan.');
                    }

                    statusMessage.textContent = result.message;
                    statusMessage.classList.add('text-success');

                } catch (error) {
                    statusMessage.textContent = 'Error: ' + error.message;
                    statusMessage.classList.add('text-danger');
                }
            });
        });
    </script>
</body>
</html>
