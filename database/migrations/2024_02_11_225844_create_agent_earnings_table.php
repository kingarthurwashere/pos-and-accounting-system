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
        Schema::create('agent_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents')->constrained();
            $table->foreign('order_id')->references('id')->on('orders')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_earnings', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropForeign(['order_id']);
        });
        Schema::dropIfExists('agent_earnings');
    }
};
