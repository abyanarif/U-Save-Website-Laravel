<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel Literasi Keuangan</title>
    <!-- Muat TinyMCE dari CDN -->
    <script src="https://cdn.tiny.cloud/1/jea0x5zq1xgq01p1iwsrad0tfmou8hl5hjj2ve8ojokkd0ld/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        body { font-family: sans-serif; padding: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        input[type="text"], select { width: 100%; padding: 0.5rem; font-size: 1rem; }
        button { padding: 0.75rem 1.5rem; background-color: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Edit Artikel Literasi Keuangan</h1>

    <div class="form-group">
        <label for="articleSelector">Pilih Artikel untuk Diedit:</label>
        <select id="articleSelector">
            <option value="">-- Pilih Bagian --</option>
            <option value="pemahaman-dasar">Pemahaman Dasar</option>
            <option value="tabungan">Tabungan dan Pinjaman</option>
            <option value="investasi">Investasi</option>
            <option value="asuransi">Asuransi</option>
            <option value="perencanaan">Perencanaan</option>
        </select>
    </div>

    <form id="editForm" style="display:none;">
        <div class="form-group">
            <label for="articleTitle">Judul Artikel:</label>
            <input type="text" id="articleTitle" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="articleContent">Isi Artikel:</label>
            <!-- Textarea ini akan diubah menjadi Rich Text Editor oleh TinyMCE -->
            <textarea id="articleContent" name="content"></textarea>
        </div>

        <div class="form-group">
            <label for="articleImage">URL Gambar (Opsional):</label>
            <input type="text" id="articleImage" name="image_url">
        </div>
        
        <button type="submit">Simpan Perubahan</button>
    </form>
    <div id="statusMessage"></div>

    <script>
        // Inisialisasi TinyMCE pada textarea
        tinymce.init({
            selector: 'textarea#articleContent',
            readonly: false, // FIX: Secara eksplisit mengatur editor agar tidak read-only
            plugins: 'lists link image table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code'
        });

        const selector = document.getElementById('articleSelector');
        const form = document.getElementById('editForm');
        const statusMessage = document.getElementById('statusMessage');
        let currentSectionId = '';

        // Event listener saat admin memilih artikel dari dropdown
        selector.addEventListener('change', async (e) => {
            currentSectionId = e.target.value;
            if (!currentSectionId) {
                form.style.display = 'none';
                return;
            }

            try {
                // Ambil data artikel yang ada dari API
                const response = await fetch(`/api/articles/${currentSectionId}`);
                if (!response.ok) throw new Error('Gagal memuat artikel.');
                
                const article = await response.json();
                
                // Isi form dengan data yang ada
                document.getElementById('articleTitle').value = article.title;
                document.getElementById('articleImage').value = article.image_url || '';
                tinymce.get('articleContent').setContent(article.content);
                
                form.style.display = 'block';
            } catch (error) {
                statusMessage.textContent = 'Error: ' + error.message;
                statusMessage.style.color = 'red';
            }
        });

        // Event listener saat admin submit form
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            statusMessage.textContent = 'Menyimpan...';
            statusMessage.style.color = 'black';

            // Ambil konten dari TinyMCE
            const content = tinymce.get('articleContent').getContent();
            const formData = {
                title: document.getElementById('articleTitle').value,
                content: content,
                image_url: document.getElementById('articleImage').value,
            };

            try {
                // Kirim data yang diperbarui ke API
                const response = await fetch(`/api/articles/${currentSectionId}`, {
                    method: 'POST', // Laravel bisa menangani POST untuk update
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Jika menggunakan CSRF
                        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(JSON.stringify(errorData.errors || 'Gagal menyimpan.'));
                }

                const result = await response.json();
                statusMessage.textContent = result.message;
                statusMessage.style.color = 'green';

            } catch (error) {
                statusMessage.textContent = 'Error: ' + error.message;
                statusMessage.style.color = 'red';
            }
        });
    </script>
</body>
</html>
