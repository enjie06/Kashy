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
        Schema::create('owner_settings', function (Blueprint $table) {
        $table->id();
        $table->boolean('tunai')->default(true);
        $table->boolean('qris')->default(true);
        $table->boolean('debit')->default(false);
        $table->string('nama_toko')->default('SND Store');
        $table->text('alamat_toko')->nullable();
        $table->string('printer')->nullable();
        $table->string('ukuran_kertas')->default('80mm');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_settings');
    }
};
