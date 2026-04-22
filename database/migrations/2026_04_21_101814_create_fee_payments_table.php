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
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_fee_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('receipt_no')->unique();

            $table->decimal('paid_amount', 10, 2);

            $table->enum('payment_mode', [
                'cash',
                'upi',
                'card',
                'bank_transfer',
                'online'
            ])->default('cash');

            // Important for online payment
            $table->string('transaction_id')->nullable();

            $table->date('payment_date');

            $table->text('remarks')->nullable();

            $table->enum('status', [
                'success',
                'pending',
                'failed',
                'refunded'
            ])->default('success');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
