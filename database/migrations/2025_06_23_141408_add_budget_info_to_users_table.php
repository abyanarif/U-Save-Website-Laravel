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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan uang saku bulanan, bisa null jika belum diatur.
            $table->decimal('monthly_pocket_money', 15, 2)->nullable()->after('phone');
            
            // Menambahkan kolom untuk menyimpan kota asal pilihan pengguna.
            $table->string('city_of_origin')->nullable()->after('monthly_pocket_money');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi di-rollback.
            $table->dropColumn(['monthly_pocket_money', 'city_of_origin']);
        });
    }
};
