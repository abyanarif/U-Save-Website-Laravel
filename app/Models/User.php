<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Pastikan model-model ini ada
use App\Models\BudgetPlanItem;
use App\Models\University;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'firebase_uid',
        'username',
        'email',
        'university_id',
        'phone',
        'role',
        // FIX: Menambahkan kolom budget agar bisa diisi
        'monthly_pocket_money',
        'city_of_origin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            // FIX: Menambahkan cast untuk memastikan tipe data yang benar
            'monthly_pocket_money' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke tabel universities.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * FIX: Mengubah relasi agar langsung ke budget items.
     * Seorang pengguna sekarang memiliki banyak item budget.
     */
    public function budgetItems()
    {
        return $this->hasMany(BudgetPlanItem::class);
    }
}
