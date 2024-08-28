<?php

use App\Enums\InvoiceStatus;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('amount_due')->nullable();
            $table->string('payable_slug');
            $table->unsignedBigInteger('initiated_by');
            $table->enum('status', array_map(fn ($status) => $status->value, InvoiceStatus::cases()))->default(InvoiceStatus::DRAFT);
            $table->string('payable_identifier')->nullable();
            $table->boolean('covers_full_balance');
            $table->datetime('payment_datetime')->nullable();
            $table->datetime('dispute_datetime')->nullable();
            $table->timestamps();

            // Ensure `slug` in `payables` is indexed and unique as needed.
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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['payable_slug']);
        });
        Schema::dropIfExists('invoices');
    }
};
