<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->unsignedBigInteger('user_id');
            $table->date('current_date');
            $table->enum('status', ['NOT_CONFIRMED', 'CONFIRMED', 'VERIFIED', 'CLOSED'])->default('NOT_CONFIRMED');
            $table->integer('opening_balance');
            $table->integer('closing_balance');
            $table->integer('total_disbursed');
            $table->integer('total_received');
            $table->integer('cash_in_hand')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('assigned_verifier')->nullable();
            $table->unsignedBigInteger('verifier')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->datetime('verified_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->foreign('verifier')->references('id')->on('users')->constrained();
            $table->foreign('assigned_verifier')->references('id')->on('users')->constrained();
            $table->foreign('location_id')->references('id')->on('locations')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['verifier']);
            $table->dropForeign(['assigned_verifier']);
            $table->dropForeign(['location_id']);
        });
        Schema::dropIfExists('daily_reports');
    }
};
