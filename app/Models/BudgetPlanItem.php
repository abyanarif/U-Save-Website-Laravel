<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPlanItem extends Model
{
    use HasFactory;

    protected $table = 'budget_plan_items';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Sesuai migrasi Anda, tidak ada timestamps di budget_plan_items

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', // FIX: Diubah dari budget_plan_id menjadi user_id
        'category_name',
        'allocated_amount',
        'is_custom',
        'original_input_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'original_input_amount' => 'decimal:2',
        'is_custom' => 'boolean',
    ];

    /**
     * Get the user that owns the budget item.
     */
    public function user()
    {
        // FIX: Mengubah relasi agar menunjuk ke User model
        return $this->belongsTo(User::class);
    }
}
