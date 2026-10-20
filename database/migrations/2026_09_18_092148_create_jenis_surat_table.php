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
          Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode', 20)->unique(); // Contoh: SR (Surat Rekomendasi)
            $table->string('template_path')->nullable();
            $table->json('fields_json'); // Schema form dinamis
            $table->integer('ttd_x')->default(400); // Koordinat X ttd di PDF
            $table->integer('ttd_y')->default(700); // Koordinat Y ttd di PDF
            $table->integer('qr_x')->default(50);
            $table->integer('qr_y')->default(750);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_surat');
    }
};