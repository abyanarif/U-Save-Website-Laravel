<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UmrData; // Menggunakan model UmrData yang terhubung ke tabel 'cities'

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum diisi untuk menghindari duplikasi
        UmrData::truncate();

        $cities = [
            ['id' => 1, 'nama_kota' => 'DKI Jakarta', 'provinsi' => 'DKI Jakarta', 'umr' => 5100000, 'harga_makan_avg' => 40000, 'harga_kos_avg' => 2500000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_kota' => 'Surabaya', 'provinsi' => 'Jawa Timur', 'umr' => 4750000, 'harga_makan_avg' => 30000, 'harga_kos_avg' => 1500000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'umr' => 4200000, 'harga_makan_avg' => 28000, 'harga_kos_avg' => 1300000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_kota' => 'Medan', 'provinsi' => 'Sumatera Utara', 'umr' => 3800000, 'harga_makan_avg' => 25000, 'harga_kos_avg' => 1000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_kota' => 'Makassar', 'provinsi' => 'Sulawesi Selatan', 'umr' => 3600000, 'harga_makan_avg' => 27000, 'harga_kos_avg' => 1200000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama_kota' => 'Semarang', 'provinsi' => 'Jawa Tengah', 'umr' => 3100000, 'harga_makan_avg' => 22000, 'harga_kos_avg' => 900000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama_kota' => 'Palembang', 'provinsi' => 'Sumatera Selatan', 'umr' => 3700000, 'harga_makan_avg' => 26000, 'harga_kos_avg' => 1000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama_kota' => 'Denpasar', 'provinsi' => 'Bali', 'umr' => 3100000, 'harga_makan_avg' => 35000, 'harga_kos_avg' => 1800000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nama_kota' => 'Yogyakarta', 'provinsi' => 'DI Yogyakarta', 'umr' => 2500000, 'harga_makan_avg' => 20000, 'harga_kos_avg' => 800000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama_kota' => 'Balikpapan', 'provinsi' => 'Kalimantan Timur', 'umr' => 3500000, 'harga_makan_avg' => 32000, 'harga_kos_avg' => 1700000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama_kota' => 'Pekanbaru', 'provinsi' => 'Riau', 'umr' => 3450000, 'harga_makan_avg' => 28000, 'harga_kos_avg' => 1100000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'nama_kota' => 'Padang', 'provinsi' => 'Sumatera Barat', 'umr' => 2900000, 'harga_makan_avg' => 25000, 'harga_kos_avg' => 900000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'nama_kota' => 'Manado', 'provinsi' => 'Sulawesi Utara', 'umr' => 3650000, 'harga_makan_avg' => 30000, 'harga_kos_avg' => 1200000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'nama_kota' => 'Jayapura', 'provinsi' => 'Papua', 'umr' => 4000000, 'harga_makan_avg' => 45000, 'harga_kos_avg' => 2000000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'nama_kota' => 'Ambon', 'provinsi' => 'Maluku', 'umr' => 2950000, 'harga_makan_avg' => 35000, 'harga_kos_avg' => 1300000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'nama_kota' => 'Banjarmasin', 'provinsi' => 'Kalimantan Selatan', 'umr' => 3300000, 'harga_makan_avg' => 26000, 'harga_kos_avg' => 950000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'nama_kota' => 'Pontianak', 'provinsi' => 'Kalimantan Barat', 'umr' => 3000000, 'harga_makan_avg' => 24000, 'harga_kos_avg' => 850000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'nama_kota' => 'Bandar Lampung', 'provinsi' => 'Lampung', 'umr' => 2800000, 'harga_makan_avg' => 23000, 'harga_kos_avg' => 800000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'nama_kota' => 'Mataram', 'provinsi' => 'Nusa Tenggara Barat', 'umr' => 2600000, 'harga_makan_avg' => 22000, 'harga_kos_avg' => 750000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'nama_kota' => 'Kupang', 'provinsi' => 'Nusa Tenggara Timur', 'umr' => 2300000, 'harga_makan_avg' => 30000, 'harga_kos_avg' => 900000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'nama_kota' => 'Malang', 'provinsi' => 'Jawa Timur', 'umr' => 3400000, 'harga_makan_avg' => 20000, 'harga_kos_avg' => 800000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'nama_kota' => 'Depok', 'provinsi' => 'Jawa Barat', 'umr' => 4900000, 'harga_makan_avg' => 30000, 'harga_kos_avg' => 1500000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'nama_kota' => 'Bogor', 'provinsi' => 'Jawa Barat', 'umr' => 4850000, 'harga_makan_avg' => 28000, 'harga_kos_avg' => 1200000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'nama_kota' => 'Surakarta (Solo)', 'provinsi' => 'Jawa Tengah', 'umr' => 2350000, 'harga_makan_avg' => 18000, 'harga_kos_avg' => 700000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'nama_kota' => 'Purwokerto', 'provinsi' => 'Jawa Tengah', 'umr' => 2250000, 'harga_makan_avg' => 17000, 'harga_kos_avg' => 650000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'nama_kota' => 'Jember', 'provinsi' => 'Jawa Timur', 'umr' => 2700000, 'harga_makan_avg' => 18000, 'harga_kos_avg' => 700000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'nama_kota' => 'Serang', 'provinsi' => 'Banten', 'umr' => 4300000, 'harga_makan_avg' => 25000, 'harga_kos_avg' => 900000, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('cities')->insert($cities);
    }
}
