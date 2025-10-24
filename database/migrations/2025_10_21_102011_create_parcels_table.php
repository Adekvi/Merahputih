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
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->enum('type', ['persawahan', 'perkebunan', 'tambak', 'peternakan', 'komoditas_lain']);
            $table->decimal('area_hectare', 10, 3)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index(['survey_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
