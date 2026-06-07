<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('discounts', function (Blueprint $table) {
        $table->boolean('semua_produk')->default(false)->after('product_id');
        $table->string('nama_promosi')->nullable()->after('semua_produk');
    });

    Schema::create('discount_categories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('discount_id')->constrained()->onDelete('cascade');
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
        });
    }
};
