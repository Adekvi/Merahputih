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
        Schema::create('parcel_crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained('parcels')->onDelete('cascade');
            $table->string('nama_tanaman');
            $table->decimal('luas_hektare', 10, 3)->nullable();
            $table->decimal('produksi_ton', 12, 3)->nullable();
            $table->string('satuan')->nullable();
            $table->text('catatan')->nullable();
            $table->index(['parcel_id', 'nama_tanaman']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcel_crops');
    }
};
