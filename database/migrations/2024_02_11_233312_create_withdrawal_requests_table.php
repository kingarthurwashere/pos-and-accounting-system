<?php

use App\Enums\WithdrawalRequestStatus;
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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('type');
            $table->string('email');
            $table->integer('amount');
            $table->boolean('agent_balance_deductible')->default(0);
            $table->enum('status', array_map(fn ($status) => $status->value, WithdrawalRequestStatus::cases()))->default(WithdrawalRequestStatus::PENDING);
            $table->string('withdrawal_method_slug')->nullable();
            $table->text('rejection_message')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->unsignedBigInteger('disbursed_by')->nullable();
            $table->unsignedBigInteger('disbursement_location_id')->nullable();
            $table->boolean('posted')->nullable();
            $table->datetime('approval_datetime')->nullable();
            $table->datetime('rejection_datetime')->nullable();
            $table->datetime('disburse_datetime')->nullable();
            $table->datetime('posted_datetime')->nullable();
            $table->timestamps();

            $table->foreign('type')->references('slug')->on('withdrawal_request_types')->constrained();
            $table->foreign('withdrawal_method_slug')->references('slug')->on('withdrawal_methods')->constrained();
            $table->foreign('approved_by')->references('id')->on('users')->constrained();
            $table->foreign('rejected_by')->references('id')->on('users')->constrained();
            $table->foreign('disbursed_by')->references('id')->on('users')->constrained();
            $table->foreign('disbursement_location_id')->references('id')->on('locations')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropForeign(['type']);
            $table->dropForeign(['withdrawal_method_slug']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['disbursed_by']);
            $table->dropForeign(['disbursement_location_id']);
        });
        Schema::dropIfExists('withdrawal_requests');
    }
};
