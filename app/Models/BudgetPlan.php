<?php // app/Models/BudgetPlan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    use HasFactory;

    protected $table = 'budget_plans';

    protected $fillable = [
        'user_id',
        'plan_name',
        'total_pocket_money',
        'city_of_origin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_pocket_money' => 'decimal:2',
    ];

    /**
     * Get the user that owns the budget plan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the budget plan.
     */
    public function items()
    {
        return $this->hasMany(BudgetPlanItem::class);
    }
}
