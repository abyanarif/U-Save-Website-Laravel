<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Periksa apakah kolom belum ada sebelum menambahkannya
        if (!Schema::hasColumn('users', 'monthly_pocket_money')) {
            Schema::table('users', function (Blueprint $table) {
                // Menambahkan kolom untuk menyimpan uang saku bulanan
                $table->decimal('monthly_pocket_money', 15, 2)->nullable()->after('phone');
            });
        }
        
        if (!Schema::hasColumn('users', 'city_of_origin')) {
            Schema::table('users', function (Blueprint $table) {
                // Menambahkan kolom untuk menyimpan kota asal pilihan pengguna
                $table->string('city_of_origin')->nullable()->after('monthly_pocket_money');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom jika ada saat rollback
            if (Schema::hasColumn('users', 'monthly_pocket_money')) {
                $table->dropColumn('monthly_pocket_money');
            }
            if (Schema::hasColumn('users', 'city_of_origin')) {
                $table->dropColumn('city_of_origin');
            }
        });
    }
};
