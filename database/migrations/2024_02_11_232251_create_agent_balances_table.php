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
        Schema::create('agent_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->integer('balance');
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_balances', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });
        Schema::dropIfExists('agent_balances');
    }
};
