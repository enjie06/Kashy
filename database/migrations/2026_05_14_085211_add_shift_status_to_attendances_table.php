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
    Schema::table('attendances', function (Blueprint $table) {
        $table->enum('shift_status', ['tidak_aktif', 'aktif', 'selesai'])->default('tidak_aktif');
    });
}

public function down()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropColumn('shift_status');
    });
}
};
