<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatusPengajuan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('no_tiket', 20)->unique();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Pemohon
        
            $table->json('data_json'); // Data dinamis dari form
        
            $table->string('status')->default(StatusPengajuan::MENGAJUKAN->value);
            $table->text('catatan_revisi')->nullable();
        
            $table->string('nomor_surat', 100)->nullable()->unique();
            $table->string('file_pdf')->nullable();
            $table->uuid('qr_token')->unique()->nullable();
        
            $table->foreignId('penandatangan_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_ttd')->nullable();
        
            $table->timestamps();
            $table->softDeletes();

            // Index untuk performa query
            $table->index('status');
            $table->index('no_tiket');
            $table->index('qr_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};