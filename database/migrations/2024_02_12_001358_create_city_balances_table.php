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
        Schema::create('city_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->unique();
            $table->bigInteger('usd_balance')->default(0);
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('city_balances', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });
        Schema::dropIfExists('city_balances');
    }
};
