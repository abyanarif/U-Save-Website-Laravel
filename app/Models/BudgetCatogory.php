<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'budget_categories';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'display_text',
        'icon_class',
    ];

    /**
     * Mendefinisikan relasi "hasOne" ke model BudgetAllocationRule.
     * Setiap kategori memiliki satu aturan alokasi yang terhubung.
     */
    public function allocationRule()
    {
        // Relasi ini menghubungkan kolom 'name' di tabel ini
        // dengan kolom 'category_name' di tabel budget_allocation_rules.
        return $this->hasOne(BudgetAllocationRule::class, 'category_name', 'name');
    }
}
