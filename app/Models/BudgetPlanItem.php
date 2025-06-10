<?php // app/Models/BudgetPlanItem.php

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

    protected $fillable = [
        'budget_plan_id',
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
     * Get the budget plan that owns the item.
     */
    public function budgetPlan()
    {
        return $this->belongsTo(BudgetPlan::class);
    }
}
