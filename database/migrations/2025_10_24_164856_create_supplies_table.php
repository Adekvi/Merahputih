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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kecamatan')->constrained('kecamatans')->onDelete('cascade');
            $table->foreignId('id_kelurahan')->nullable()->constrained('kelurahans')->onDelete('cascade');
            $table->string('nama_supplier')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('kategori')->nullable();
            $table->date('tgl_sup')->nullable();
            $table->integer('jumlah')->nullable();
            $table->foreignId('satuan_jumlah_id')->constrained('satuans');
            $table->decimal('harga', 15, 2)->nullable();
            $table->foreignId('satuan_harga_id')->constrained('satuans');
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
