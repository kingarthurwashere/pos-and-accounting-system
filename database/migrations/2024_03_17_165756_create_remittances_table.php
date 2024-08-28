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
        //Syncs with 'Awaiting pick-up' remittances
        Schema::create('remittances', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->unsignedBigInteger('funded_location_id')->nullable();
            $table->unsignedBigInteger('disburse_location_id')->nullable();
            $table->integer('amount');
            $table->integer('receivable');
            $table->enum('status', [
                'AWAITING PICKUP',
                'REJECTED',
                'ACCEPTED',
                'DISBURSED',
            ])->default('AWAITING PICKUP');
            $table->string('receiver_name');
            $table->string('withdrawal_method_slug')->default('CASH');
            $table->string('receiver_email')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->string('sender_name');
            $table->string('agent_name')->nullable();
            $table->string('funded_city')->nullable();
            $table->boolean('posted')->default(false);
            $table->datetime('due_at');
            $table->datetime('accepted_at')->nullable();
            $table->datetime('rejected_at')->nullable();
            $table->datetime('disbursed_at')->nullable();
            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->unsignedBigInteger('disbursed_by')->nullable();
            $table->text('rejection_message')->nullable();
            $table->timestamps();

            $table->foreign('funded_location_id')->references('id')->on('locations')->constrained();
            $table->foreign('disburse_location_id')->references('id')->on('locations')->constrained();
            $table->foreign('withdrawal_method_slug')->references('slug')->on('withdrawal_methods')->constrained();
            $table->foreign('accepted_by')->references('id')->on('users')->constrained();
            $table->foreign('rejected_by')->references('id')->on('users')->constrained();
            $table->foreign('disbursed_by')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remittances', function (Blueprint $table) {
            $table->dropForeign(['funded_location_id']);
            $table->dropForeign(['disburse_location_id']);
            $table->dropForeign(['accepted_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['disbursed_by']);
        });

        Schema::dropIfExists('remittances');
    }
};
