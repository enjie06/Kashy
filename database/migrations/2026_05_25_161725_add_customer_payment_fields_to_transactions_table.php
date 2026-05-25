<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('kasir_id');
            $table->string('payment_method')->nullable()->after('customer_name');
            $table->integer('discount_percent')->default(0)->after('diskon');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'payment_method',
                'discount_percent',
            ]);
        });
    }
};