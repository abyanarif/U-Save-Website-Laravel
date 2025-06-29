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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kota');
            $table->string('provinsi');
            $table->integer('umr')->unsigned();
            $table->integer('harga_makan_avg')->unsigned()->nullable();
            $table->integer('harga_kos_avg')->unsigned()->nullable();
            $table->integer('harga_transport_avg')->unsigned()->nullable();
            $table->integer('harga_paket_data_avg')->unsigned()->nullable();
            $table->integer('jumlah_hari_kuliah')->unsigned()->nullable();
            $table->float('inflasi_tahunan', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
