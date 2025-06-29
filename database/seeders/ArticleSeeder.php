<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Article; // Pastikan model Article Anda ada

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu untuk menghindari duplikasi jika seeder dijalankan lagi
        Article::truncate();

        $articles = [
            [
                'section_id' => 'pemahaman-dasar',
                'title' => 'Pemahaman Dasar Keuangan Pribadi',
                'content' => '<h3>Konsep Dasar Keuangan</h3><ul class="paragraft"><li>... (salin konten HTML dari file literasi_keuangan.html Anda untuk bagian ini) ...</li></ul>',
                'image_url' => 'img/pemahaman-dasar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => 'tabungan',
                'title' => 'Tabungan dan Pinjaman',
                'content' => '<h3>Tabungan</h3><p>...</p><h3>Bunga Tabungan dan Pinjaman</h3>...',
                'image_url' => null, // Tidak ada gambar untuk bagian ini
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => 'investasi',
                'title' => 'Investasi',
                'content' => '<span class="section-title">Investasi</span><p>...</p>',
                'image_url' => 'img/investasi.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => 'asuransi',
                'title' => 'Asuransi',
                'content' => '<span class="section-title-dark">Fungsi</span><p>...</p>',
                'image_url' => null, // Tidak ada gambar untuk bagian ini
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section_id' => 'perencanaan',
                'title' => 'Perencanaan Keuangan Jangka Panjang',
                'content' => '<span class="section-title">Tujuan</span><ul>...</ul>',
                'image_url' => 'img/perencanaan.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke dalam tabel articles
        DB::table('articles')->insert($articles);
    }
}
