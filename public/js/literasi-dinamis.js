document.addEventListener('DOMContentLoaded', async () => {
    // FIX: Menggunakan URL relatif agar otomatis menggunakan domain dan protokol yang benar (HTTPS)
    const API_ENDPOINT = '/api/articles'; 

    try {
        // 1. Ambil semua data artikel dari API
        const response = await fetch(API_ENDPOINT);
        if (!response.ok) {
            throw new Error(`Gagal memuat data. Server merespons dengan status: ${response.status}`);
        }
        const articles = await response.json();
        
        if (!articles || articles.length === 0) {
            document.querySelectorAll('.keterangan-content-literasi, .keterangan-content-tabungan, .keterangan-content-investasi, .keterangan-content-asuransi, .keterangan-content-perencanaan').forEach(el => {
                if(el) el.innerHTML = '<h1>Belum ada artikel yang tersedia.</h1><p>Silakan kembali lagi nanti.</p>';
            });
            return;
        }

        // 2. Petakan section_id ke ID elemen HTML
        const elementMapping = {
            'pemahaman-dasar': { contentId: 'content-pemahaman-dasar', imgId: 'img-pemahaman-dasar' },
            'tabungan': { contentId: 'content-tabungan' },
            'investasi': { contentId: 'content-investasi', imgId: 'img-investasi' },
            'asuransi': { contentId: 'content-asuransi' },
            'perencanaan': { contentId: 'content-perencanaan', imgId: 'img-perencanaan' }
        };

        // 3. Loop melalui setiap artikel dan masukkan ke dalam HTML
        articles.forEach(article => {
            const mapping = elementMapping[article.section_id];
            if (mapping) {
                const contentElement = document.getElementById(mapping.contentId);
                if (contentElement) {
                    contentElement.innerHTML = `<h1>${article.title}</h1><div>${article.content}</div>`;
                }

                const imgElement = document.getElementById(mapping.imgId);
                if (imgElement && article.image_url) {
                    // FIX: Memastikan URL gambar juga relatif jika disimpan di server yang sama
                    imgElement.src = `/${article.image_url}`; 
                    imgElement.alt = article.title;
                } else if (imgElement) {
                    imgElement.style.display = 'none';
                }
            }
        });

    } catch (error) {
        console.error('Error fetching articles:', error);
        let errorMessage = '<b>Gagal terhubung ke server.</b> Pastikan server Anda aktif dan konfigurasi CORS sudah benar.';
        if (error.message.includes('Failed to fetch')) {
             errorMessage += '<br>Ini seringkali disebabkan oleh masalah koneksi atau CORS.';
        } else {
             errorMessage += `<br><br>Detail: ${error.message}`;
        }

        document.querySelectorAll('.keterangan-content-literasi, .keterangan-content-tabungan, .keterangan-content-investasi, .keterangan-content-asuransi, .keterangan-content-perencanaan').forEach(el => {
            el.innerHTML = `<h1>Gagal Memuat Konten</h1><p style="color: red;">${errorMessage}</p>`;
        });
    }
});

// Anda bisa menambahkan kembali script untuk menu mobile di sini jika perlu
const menuIcon = document.getElementById("menu-icon");
const menuList = document.getElementById("menu-list");

if(menuIcon && menuList){
  menuIcon.addEventListener("click", () => {
    menuList.classList.toggle("hidden");
  });
}
