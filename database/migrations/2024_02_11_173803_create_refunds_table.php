<?php

use App\Enums\RefundStatus;
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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->integer('amount');
            $table->string('refund_method');
            $table->enum('status', array_map(fn ($status) => $status->value, RefundStatus::cases()))->default(RefundStatus::INITIATED);
            $table->text('notes');
            $table->unsignedBigInteger('initiated_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->unsignedBigInteger('initiation_location_id')->nullable();
            $table->unsignedBigInteger('disbursed_by')->nullable();
            $table->unsignedBigInteger('disbursement_location_id')->nullable();
            $table->datetime('initiation_datetime')->nullable();
            $table->datetime('disbursement_datetime')->nullable();
            $table->datetime('approval_datetime')->nullable();
            $table->datetime('rejection_datetime')->nullable();
            $table->boolean('posted')->nullable();
            $table->timestamps();

            $table->foreign('initiated_by')->references('id')->on('users');
            $table->foreign('rejected_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('disbursed_by')->references('id')->on('users');
            $table->foreign('initiation_location_id')->references('id')->on('locations');
            $table->foreign('disbursement_location_id')->references('id')->on('locations');
            $table->foreign('refund_method')->references('slug')->on('refund_methods');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropForeign(['initiated_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['disbursed_by']);
            $table->dropForeign(['initiation_location_id']);
            $table->dropForeign(['disbursement_location_id']);
            $table->dropForeign(['payment_id']);
            $table->dropForeign(['refund_method']);
        });
        Schema::dropIfExists('refunds');
    }
};
