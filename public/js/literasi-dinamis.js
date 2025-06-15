document.addEventListener("DOMContentLoaded", async () => {
    // Tentukan URL dasar lengkap untuk backend Laravel Anda.
    const API_BASE_URL = "http://u-save-website.test:8080";

    try {
        // 1. Ambil semua data artikel dari API menggunakan URL lengkap
        const response = await fetch(`${API_BASE_URL}/api/articles`);
        if (!response.ok) {
            throw new Error(
                `Gagal memuat data. Server merespons dengan status: ${response.status}`
            );
        }
        const articles = await response.json();

        // Pengecekan jika tidak ada artikel yang ditemukan
        if (!articles || articles.length === 0) {
            document
                .querySelectorAll(
                    ".keterangan-content-literasi, .keterangan-content-tabungan, .keterangan-content-investasi, .keterangan-content-asuransi, .keterangan-content-perencanaan"
                )
                .forEach((el) => {
                    if (el)
                        el.innerHTML =
                            "<h1>Belum ada artikel yang tersedia.</h1><p>Silakan kembali lagi nanti.</p>";
                });
            return; // Hentikan eksekusi jika tidak ada artikel
        }

        // 2. Petakan section_id ke ID elemen HTML
        const elementMapping = {
            "pemahaman-dasar": {
                contentId: "content-pemahaman-dasar",
                imgId: "img-pemahaman-dasar",
            },
            tabungan: {
                contentId: "content-tabungan",
            },
            investasi: {
                contentId: "content-investasi",
                imgId: "img-investasi",
            },
            asuransi: {
                contentId: "content-asuransi",
            },
            perencanaan: {
                contentId: "content-perencanaan",
                imgId: "img-perencanaan",
            },
        };

        // 3. Loop melalui setiap artikel dan masukkan ke dalam HTML
        articles.forEach((article) => {
            const mapping = elementMapping[article.section_id];
            if (mapping) {
                const contentElement = document.getElementById(
                    mapping.contentId
                );
                if (contentElement) {
                    contentElement.innerHTML = `<h1>${article.title}</h1><div>${article.content}</div>`;
                }

                const imgElement = document.getElementById(mapping.imgId);
                if (imgElement && article.image_url) {
                    imgElement.src = article.image_url;
                    imgElement.alt = article.title;
                } else if (imgElement) {
                    imgElement.style.display = "none";
                }
            }
        });
    } catch (error) {
        console.error("Error fetching articles:", error);

        let errorMessage =
            "<b>Gagal terhubung ke server.</b> Ini biasanya disebabkan oleh salah satu dari dua hal:<br><br>";

        if (
            error instanceof TypeError &&
            error.message.includes("Failed to fetch")
        ) {
            errorMessage +=
                '<b>1. Server Laravel Tidak Berjalan atau Tidak Dapat Diakses:</b> Pastikan server Anda (di Laragon atau lainnya) sudah aktif dan dapat diakses di <a href="' +
                API_BASE_URL +
                '" target="_blank">' +
                API_BASE_URL +
                "</a>.<br><br>";
            errorMessage +=
                '<b>2. Masalah CORS (Sangat Mungkin):</b> Error "Failed to fetch" adalah indikasi kuat bahwa backend Laravel Anda memblokir permintaan dari halaman ini karena kebijakan CORS. <br><b>Solusi:</b> Anda perlu mengizinkan permintaan dari origin ini di file konfigurasi CORS Laravel Anda (`config/cors.php`).';
        } else {
            errorMessage += `Detail error lain: ${error.message}`;
        }

        // Tampilkan pesan error jika gagal memuat
        document
            .querySelectorAll(
                ".keterangan-content-literasi, .keterangan-content-tabungan, .keterangan-content-investasi, .keterangan-content-asuransi, .keterangan-content-perencanaan"
            )
            .forEach((el) => {
                el.innerHTML = `<h1>Gagal Memuat Konten</h1><p style="color: red;">${errorMessage}</p>`;
            });
    }
});

// Anda bisa menambahkan kembali script untuk menu mobile di sini jika perlu
const menuIcon = document.getElementById("menu-icon");
const menuList = document.getElementById("menu-list");

if (menuIcon && menuList) {
    menuIcon.addEventListener("click", () => {
        menuList.classList.toggle("hidden");
    });
}
