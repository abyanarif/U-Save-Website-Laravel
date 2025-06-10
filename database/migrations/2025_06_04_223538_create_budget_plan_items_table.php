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
        Schema::create('budget_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_plan_id')
                  ->constrained('budget_plans') // Mereferensikan kolom 'id' di tabel 'budget_plans'
                  ->onDelete('cascade'); // Jika budget plan dihapus, item terkait juga dihapus
            $table->string('category_name', 100);
            $table->decimal('allocated_amount', 15, 2);
            $table->boolean('is_custom')->default(false);
            $table->decimal('original_input_amount', 15, 2)->nullable();
            // Tidak ada $table->timestamps(); di sini agar konsisten dengan desain SQL sebelumnya
            // Jika Anda memerlukannya, Anda bisa menambahkannya.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_plan_items');
    }
};