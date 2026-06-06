<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('category_logs', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->string('category_name');
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_logs');
    }
};