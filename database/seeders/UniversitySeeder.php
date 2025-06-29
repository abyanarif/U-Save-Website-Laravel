<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\University;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel untuk menghindari duplikasi saat seeder dijalankan ulang
        University::truncate();

        $universities = [
            // DKI Jakarta (city_id = 1)
            ['name' => 'Universitas Indonesia', 'city_id' => 1, 'address' => 'Kampus UI Depok, Pondok Cina, Beji, Kota Depok, Jawa Barat 16424 / Kampus UI Salemba, Jl. Salemba Raya No.4, RW.5, Kenari, Senen, Kota Jakarta Pusat, DKI Jakarta 10430', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Negeri Jakarta', 'city_id' => 1, 'address' => 'Jl. Rawamangun Muka Raya, RT.11/RW.14, Rawamangun, Pulo Gadung, Kota Jakarta Timur, DKI Jakarta 13220', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Bina Nusantara (BINUS)', 'city_id' => 1, 'address' => 'Jl. K. H. Syahdan No.9, Palmerah, Jakarta Barat, DKI Jakarta 11480', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Trisakti', 'city_id' => 1, 'address' => 'Jl. Kyai Tapa No.1, Grogol, Grogol Petamburan, Jakarta Barat, DKI Jakarta 11440', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Katolik Indonesia Atma Jaya', 'city_id' => 1, 'address' => 'Jl. Jend. Sudirman No.51, Karet Semanggi, Setiabudi, Jakarta Selatan, DKI Jakarta 12930', 'created_at' => now(), 'updated_at' => now()],
            
            // Surabaya (city_id = 2)
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'city_id' => 2, 'address' => 'Jl. Teknik Kimia, Keputih, Sukolilo, Kota Surabaya, Jawa Timur 60111', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Airlangga', 'city_id' => 2, 'address' => 'Jl. Airlangga No.4 - 6, Airlangga, Gubeng, Kota Surabaya, Jawa Timur 60115', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Negeri Surabaya', 'city_id' => 2, 'address' => 'Jl. Lidah Wetan, Lidah Wetan, Lakarsantri, Kota Surabaya, Jawa Timur 60213', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Kristen Petra', 'city_id' => 2, 'address' => 'Jl. Siwalankerto No.121-131, Siwalankerto, Wonocolo, Kota Surabaya, Jawa Timur 60236', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Surabaya (UBAYA)', 'city_id' => 2, 'address' => 'Jl. Ngagel Jaya Selatan No.169, Sidosermo, Wonocolo, Kota Surabaya, Jawa Timur 60284', 'created_at' => now(), 'updated_at' => now()],

            // Bandung (city_id = 3)
            ['name' => 'Institut Teknologi Bandung', 'city_id' => 3, 'address' => 'Jl. Ganesa No.10, Lb. Siliwangi, Coblong, Kota Bandung, Jawa Barat 40132', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Padjadjaran', 'city_id' => 3, 'address' => 'Jl. Raya Bandung Sumedang KM.21, Jatinangor, Sumedang, Jawa Barat 45363 (Kampus Utama) / Jl. Dipati Ukur No.35, Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Pendidikan Indonesia', 'city_id' => 3, 'address' => 'Jl. Dr. Setiabudi No.229, Isola, Sukasari, Kota Bandung, Jawa Barat 40154', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Katolik Parahyangan', 'city_id' => 3, 'address' => 'Jl. Ciumbuleuit No.94, Hegarmanah, Cidadap, Kota Bandung, Jawa Barat 40141', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Telkom University', 'city_id' => 3, 'address' => 'Jl. Telekomunikasi No. 1, Terusan Buahbatu, Sukapura, Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40257', 'created_at' => now(), 'updated_at' => now()],

            // Medan (city_id = 4)
            ['name' => 'Universitas Sumatera Utara', 'city_id' => 4, 'address' => 'Jl. Dr. Mansyur No.9, Padang Bulan, Medan Baru, Kota Medan, Sumatera Utara 20222', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Negeri Medan', 'city_id' => 4, 'address' => 'Jl. Willem Iskandar Ps. V, Medan Estate, Percut Sei Tuan, Kabupaten Deli Serdang, Sumatera Utara 20221', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas HKBP Nommensen', 'city_id' => 4, 'address' => 'Jl. Sutomo No.4A, Gaharu, Medan Timur, Kota Medan, Sumatera Utara 20235', 'created_at' => now(), 'updated_at' => now()],

            // Makassar (city_id = 5)
            ['name' => 'Universitas Hasanuddin', 'city_id' => 5, 'address' => 'Jl. Perintis Kemerdekaan KM.10, Tamalanrea Indah, Tamalanrea, Kota Makassar, Sulawesi Selatan 90245', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Negeri Makassar', 'city_id' => 5, 'address' => 'Jl. A. P. Pettarani, Tidung, Rappocini, Kota Makassar, Sulawesi Selatan 90222', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Muslim Indonesia', 'city_id' => 5, 'address' => 'Jl. Urip Sumoharjo KM.5, Panaikang, Panakkukang, Kota Makassar, Sulawesi Selatan 90231', 'created_at' => now(), 'updated_at' => now()],

            // Semarang (city_id = 6)
            ['name' => 'Universitas Diponegoro', 'city_id' => 6, 'address' => 'Jl. Prof. Soedarto, SH, Tembalang, Kota Semarang, Jawa Tengah 50275', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Negeri Semarang', 'city_id' => 6, 'address' => 'Kampus Sekaran, Gunungpati, Kota Semarang, Jawa Tengah 50229', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Katolik Soegijapranata', 'city_id' => 6, 'address' => 'Jl. Pawiyatan Luhur IV No.1, Bendan Duwur, Gajahmungkur, Kota Semarang, Jawa Tengah 50234', 'created_at' => now(), 'updated_at' => now()],
            
            // Yogyakarta (city_id = 9)
            ['name' => 'Universitas Gadjah Mada', 'city_id' => 9, 'address' => 'Bulaksumur, Caturtunggal, Depok, Sleman, Daerah Istimewa Yogyakarta 55281', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Negeri Yogyakarta', 'city_id' => 9, 'address' => 'Jl. Colombo No.1, Karang Malang, Caturtunggal, Depok, Sleman, Daerah Istimewa Yogyakarta 55281', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Islam Indonesia', 'city_id' => 9, 'address' => 'Jl. Kaliurang KM.14,5, Krawitan, Umbulmartani, Ngemplak, Sleman, Daerah Istimewa Yogyakarta 55584', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Muhammadiyah Yogyakarta', 'city_id' => 9, 'address' => 'Jl. Brawijaya, Geblagan, Tamantirto, Kasihan, Bantul, Daerah Istimewa Yogyakarta 55183', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Atma Jaya Yogyakarta', 'city_id' => 9, 'address' => 'Jl. Babarsari No.44, Janti, Caturtunggal, Depok, Sleman, Daerah Istimewa Yogyakarta 55281', 'created_at' => now(), 'updated_at' => now()],

            // Malang (city_id = 21)
            ['name' => 'Universitas Brawijaya', 'city_id' => 21, 'address' => 'Jl. Veteran, Ketawanggede, Lowokwaru, Kota Malang, Jawa Timur 65145', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Negeri Malang', 'city_id' => 21, 'address' => 'Jl. Semarang No.5, Sumbersari, Lowokwaru, Kota Malang, Jawa Timur 65145', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Muhammadiyah Malang', 'city_id' => 21, 'address' => 'Jl. Raya Tlogomas No.246, Babatan, Tegalgondo, Karangploso, Kota Malang, Jawa Timur 65152', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Institut Teknologi Nasional Malang', 'city_id' => 21, 'address' => 'Jl. Bendungan Sigura-gura No.2, Sumbersari, Lowokwaru, Kota Malang, Jawa Timur 65145', 'created_at' => now(), 'updated_at' => now()],
            
            // dan seterusnya untuk kota lain...
        ];
        
        DB::table('universities')->insert($universities);
    }
}
