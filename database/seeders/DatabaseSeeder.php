<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // FIX: Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // FIX: Nonaktifkan foreign key check sebelum seeding
        Schema::disableForeignKeyConstraints();

        // Panggil seeder lain yang sudah Anda buat
        $this->call([
            ArticleSeeder::class,
            UniversitySeeder::class,
            // Anda bisa menambahkan seeder lain di sini jika ada
        ]);

        // Membuat 10 user dummy menggunakan factory yang sudah diperbaiki
        User::factory(10)->create();

        // FIX: Aktifkan kembali foreign key check setelah seeding selesai
        Schema::enableForeignKeyConstraints();
    }
}
