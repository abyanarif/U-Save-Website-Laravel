<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('budget_plan_items', function (Blueprint $table) {
            // FIX - LANGKAH 1: Hapus foreign key constraint yang lama terlebih dahulu.
            // Laravel akan secara otomatis menemukan nama constraint yang benar dari nama kolom.
            $table->dropForeign(['budget_plan_id']);

            // FIX - LANGKAH 2: Setelah constraint dihapus, baru hapus kolomnya.
            $table->dropColumn('budget_plan_id');

            // LANGKAH 3: Tambahkan kolom user_id yang baru beserta foreign key-nya.
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_plan_items', function (Blueprint $table) {
            // Lakukan kebalikannya jika Anda perlu melakukan rollback
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('budget_plan_id'); // Kembalikan kolom lama
        });
    }
};
