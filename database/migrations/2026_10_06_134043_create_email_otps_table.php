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
    Schema::create('email_otps', function (Blueprint $table) {
        $table->id();
        $table->string('email')->index();
        $table->string('purpose', 30); // registration | activation
        $table->string('code_hash');
        $table->unsignedTinyInteger('attempts')->default(0);
        $table->timestamp('expires_at');
        $table->timestamp('consumed_at')->nullable();
        $table->timestamps();

        $table->index(['email', 'purpose']);
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropTable('email_otps');
}
};