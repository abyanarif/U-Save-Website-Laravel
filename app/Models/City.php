<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities'; // Menyatakan nama tabel di database

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'umr' => 'integer',
        'harga_makan_avg' => 'integer',
        'harga_kos_avg' => 'integer',
        'harga_transport_avg' => 'integer',
        'harga_paket_data_avg' => 'integer',
        'jumlah_hari_kuliah' => 'integer',
        'inflasi_tahunan' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model University.
     * Sebuah kota bisa memiliki banyak universitas.
     */
    public function universities()
    {
        // Pastikan namespace App\Models\University sudah benar
        // dan model University juga sudah Anda buat.
        return $this->hasMany(University::class, 'city_id', 'id');
    }
}
