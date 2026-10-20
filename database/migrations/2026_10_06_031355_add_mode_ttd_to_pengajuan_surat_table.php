<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->enum('mode_ttd', ['digital', 'manual'])
                ->nullable()
                ->after('tanggal_ttd');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn('mode_ttd');
        });
    }
};