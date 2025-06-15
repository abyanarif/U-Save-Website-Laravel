<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('budget_plan_items', function (Blueprint $table) {
            // Hapus foreign key yang lama jika sudah ada
            // Nama constraint bisa berbeda, cek di database Anda jika ada error
            // $table->dropForeign(['budget_plan_id']); 
            $table->dropColumn('budget_plan_id');

            // Tambahkan kolom user_id baru
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('budget_plan_items', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('budget_plan_id'); // Kembalikan kolom lama
        });
    }
};
