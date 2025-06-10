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

    protected $fillable = [
        'firebase_uid',
        'name',
        'username',
        'email',
        'password',        // Mungkin opsional jika login murni via Firebase
        'university_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke tabel universities
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    // Relasi ke tabel budget_plans
    // Asumsi satu user memiliki satu budget plan utama
    public function budgetPlan()
    {
        return $this->hasOne(BudgetPlan::class);
    }

    // Jika satu user bisa memiliki banyak budget plan:
    // public function budgetPlans()
    // {
    //     return $this->hasMany(BudgetPlan::class);
    // }
}