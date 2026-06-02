<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko')->default('Kashy Store');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('jalan')->nullable();
            $table->string('kota')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('negara')->default('Indonesia');
            $table->timestamps();
        });

        // Insert default data
        DB::table('store_settings')->insert([
            'nama_toko'  => 'Kashy Store',
            'email'      => 'contact@kashy.id',
            'phone'      => '+62 812-3456-7890',
            'jalan'      => 'Jl. Gatot Subroto No.123',
            'kota'       => 'Medan',
            'kode_pos'   => '20112',
            'negara'     => 'Indonesia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};