<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Tambahkan use statement untuk BudgetPlan jika belum ada di bagian atas file
use App\Models\BudgetPlan;
use App\Models\University; // Asumsi model University sudah ada

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Disesuaikan agar cocok dengan struktur tabel di database Anda.
     */
    protected $fillable = [
        'firebase_uid',
        'username', // Tidak ada 'name' di tabel Anda, hanya 'username'
        'email',
        'university_id',
        'phone',
        'role', // Menambahkan kolom 'role' yang ada di tabel Anda
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        // 'password', // Dihapus karena tidak ada kolom password di tabel Anda
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime', // Tidak ada kolom ini di tabel Anda
            // 'password' => 'hashed', // Tidak ada kolom ini di tabel Anda
        ];
    }

    // Relasi ke tabel universities
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    // Relasi ke tabel budget_plans
    public function budgetPlan()
    {
        return $this->hasOne(BudgetPlan::class);
    }
}
