<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key check untuk menghindari error saat truncate
        Schema::disableForeignKeyConstraints();

        // Panggil semua seeder data master Anda di sini
        $this->call([
            ArticleSeeder::class,      // Asumsi Anda sudah punya ini
            CitySeeder::class,         // Asumsi Anda sudah punya ini
            UniversitySeeder::class,   // Asumsi Anda sudah punya ini
        ]);

        // Membuat 10 pengguna acak untuk data dummy
        User::factory(10)->create();
        
        // FIX: Selalu buat kembali pengguna utama Anda di sini
        // Metode `firstOrCreate` akan mencari user berdasarkan email.
        // Jika tidak ada, ia akan membuat user baru dengan data yang disediakan.
        // Ini memastikan user utama Anda selalu ada setelah seeding.
        
         User::firstOrCreate(
            ['email' => 'abyantania@gmail.com'],
            [
                'username' => 'taniacantik',
                'firebase_uid' => 'ZN0s7EX91eVvJ1pRnDuLXeWKi8q2', // Pastikan UID ini benar
                'university_id' => 7, // Sesuaikan jika perlu
                'phone' => '081231978810',
                'role' => 'user'
            ]
        );

        // Aktifkan kembali foreign key check setelah semua seeder selesai
        Schema::enableForeignKeyConstraints();
    }
}
