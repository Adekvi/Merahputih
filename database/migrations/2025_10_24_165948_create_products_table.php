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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');            
            $table->foreignId('id_kecamatan')->nullable()->constrained('kecamatans')->onDelete('set null');
            $table->foreignId('id_kelurahan')->nullable()->constrained('kelurahans')->onDelete('set null');            
            $table->foreignId('supply_id')->nullable()->constrained('supplies')->onDelete('set null');
            $table->foreignId('demand_id')->nullable()->constrained('demands')->onDelete('set null');            
            $table->string('nama_barang')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('jumlah')->nullable();
            $table->foreignId('satuan_jumlah_id')->constrained('satuans');
            $table->decimal('harga', 15, 2)->nullable();
            $table->foreignId('satuan_harga_id')->constrained('satuans');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
