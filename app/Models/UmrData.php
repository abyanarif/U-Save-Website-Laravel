<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmrData extends Model
{
    use HasFactory;

    /**
     * Secara eksplisit memberitahu Laravel untuk menggunakan tabel 'cities'.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Ini harus cocok dengan nama kolom di tabel 'cities' Anda.
     */
    protected $fillable = [
        'nama_kota',
        'provinsi',
        'umr',
        'harga_makan_avg',
        'harga_kos_avg',
        'harga_transport_avg',
        'harga_paket_data_avg',
        'jumlah_hari_kuliah',
        'inflasi_tahunan',
        'catatan',
    ];
}
