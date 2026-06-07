<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Buat tabel pivot untuk relasi many-to-many
        Schema::create('category_discount', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Tambah kolom baru ke discounts
        Schema::table('discounts', function (Blueprint $table) {
            if (!Schema::hasColumn('discounts', 'nama_promosi')) {
                $table->string('nama_promosi')->nullable()->after('id');
            }
            if (!Schema::hasColumn('discounts', 'semua_produk')) {
                $table->boolean('semua_produk')->default(false)->after('nama_promosi');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_discount');
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['nama_promosi', 'semua_produk']);
        });
    }
};