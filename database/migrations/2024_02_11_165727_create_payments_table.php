<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->integer('tender')->nullable();
            $table->integer('received_amount')->nullable();
            $table->integer('change_amount')->nullable();
            $table->string('payable_slug');
            $table->unsignedBigInteger('initiated_by');
            $table->enum('status', array_map(fn ($status) => $status->value, PaymentStatus::cases()))->default(PaymentStatus::RECEIVED);
            $table->unsignedBigInteger('receiving_cashier')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->integer('opening_balance')->nullable();
            $table->integer('closing_balance')->nullable();
            $table->string('payable_identifier')->nullable();
            $table->boolean('covers_full_balance');
            $table->boolean('posted')->nullable();
            $table->boolean('refund_posted')->nullable();
            $table->datetime('posted_datetime')->nullable();
            $table->datetime('refund_posted_datetime')->nullable();
            $table->datetime('received_amount_datetime')->nullable();
            $table->timestamps();

            // Ensure `slug` in `payables` is indexed and unique as needed.
            $table->foreign('invoice_id')->references('id')->on('invoices'); //->onDelete('cascade');
            $table->foreign('initiated_by')->references('id')->on('users'); //->onDelete('cascade');
            $table->foreign('receiving_cashier')->references('id')->on('users'); //->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations'); //->onDelete('cascade');
            $table->foreign('payable_slug')->references('slug')->on('payables'); //->onDelete('set null');
            // Optional cascade or set null actions commented out for your consideration
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // If you encounter issues with dropping foreign keys, ensure your DB supports this syntax.
            // Otherwise, you may need to specify the constraint names directly.
            $table->dropForeign(['invoice_id']);
            $table->dropForeign(['initiated_by']);
            $table->dropForeign(['receiving_cashier']);
            $table->dropForeign(['location_id']);
            $table->dropForeign(['payable_slug']);
        });
        Schema::dropIfExists('payments');
    }
};
