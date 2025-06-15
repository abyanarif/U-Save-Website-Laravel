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
        // Kosongkan tabel sebelum diisi
        University::truncate();

        $universities = [
            ['name' => 'Universitas Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Institut Teknologi Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Gadjah Mada', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Institut Pertanian Bogor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Airlangga', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Universitas Diponegoro', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('universities')->insert($universities);
    }
}
