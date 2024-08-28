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
        Schema::create('location_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id')->unique();
            $table->bigInteger('usd_balance')->default(0);
            $table->bigInteger('opening_balance')->default(500000);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('location_balances', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
        });
        Schema::dropIfExists('location_balances');
    }
};
