<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetAllocationRule extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'budget_allocation_rules';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'weight',
    ];

    /**
     * Mendefinisikan relasi ke model BudgetCategory.
     * Asumsi 'category_name' di tabel ini merujuk ke kolom 'name' di tabel budget_categories.
     */
    public function category()
    {
        return $this->belongsTo(BudgetCategory::class, 'category_name', 'name');
    }
}
