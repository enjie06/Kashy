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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();

            $table->date('tanggal');

            $table->string('nama_produk');

            $table->string('kategori');

            $table->integer('harga')->default(0);

            $table->integer('stok_sistem')->default(0);

            $table->integer('stok_fisik')->default(0);

            $table->integer('selisih')->default(0);

            $table->enum('status', [
                'match',
                'short',
                'excess'
            ])->default('match');

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};