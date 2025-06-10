<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            // Kolom unik untuk mengidentifikasi setiap bagian, cth: 'pemahaman-dasar', 'tabungan', 'investasi'
            $table->string('section_id')->unique(); 
            $table->string('title'); // Judul artikel, cth: "Pemahaman Dasar Keuangan Pribadi"
            $table->longText('content'); // Kolom untuk menyimpan isi artikel dalam format HTML
            $table->string('image_url')->nullable(); // URL gambar untuk setiap bagian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
