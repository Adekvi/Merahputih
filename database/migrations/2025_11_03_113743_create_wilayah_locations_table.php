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
        Schema::create('wilayah_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelurahan_id')->nullable()->index(); // jika kamu punya id PK di kelurahans
            $table->unsignedBigInteger('id_kecamatan')->nullable()->index(); // kode BPS kecamatan
            $table->string('nama_kelurahan', 191)->nullable()->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 11, 7)->nullable();
            $table->string('provider', 50)->nullable(); // e.g. 'nominatim'|'google'
            $table->text('raw_response')->nullable(); // simpan json raw untuk audit
            $table->timestamps();

            $table->unique(['id_kecamatan', 'nama_kelurahan'], 'wilayah_locations_kec_nama_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_locations');
    }
};
