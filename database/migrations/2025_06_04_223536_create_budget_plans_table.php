<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users') // Mereferensikan kolom 'id' di tabel 'users'
                  ->onDelete('cascade'); // Jika user dihapus, budget plan terkait juga dihapus
            $table->string('plan_name')->default('Anggaran Saya');
            $table->decimal('total_pocket_money', 15, 2);
            $table->string('city_of_origin', 100)->nullable();
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_plans');
    }
};