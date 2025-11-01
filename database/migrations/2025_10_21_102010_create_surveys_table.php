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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            // Relasi ke users (jika user dihapus, survey ikut terhapus)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Nomor unik survey (misal: KEC-DESA-20251021-0001)
            $table->string('no_id')->unique();

            // ✅ Relasi ke kecamatan & kelurahan
            $table->foreignId('kecamatan_id')
                ->nullable()
                ->constrained('kecamatans')
                ->nullOnDelete();

            $table->foreignId('kelurahan_id')
                ->nullable()
                ->constrained('kelurahans')
                ->nullOnDelete();

            $table->integer('jumlah_penduduk')->nullable();

            // Optional: tambahkan index untuk efisiensi pencarian
            $table->index(['kecamatan_id', 'kelurahan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
