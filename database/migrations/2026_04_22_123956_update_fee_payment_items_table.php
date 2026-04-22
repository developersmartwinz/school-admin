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
        //
		Schema::table('fee_payment_items', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Add Parent Fee Payment Relation
            |--------------------------------------------------------------------------
            */
            $table->foreignId('fee_payment_id')
                ->after('id')
                ->constrained('fee_payments')
                ->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | Add Student Fee Relation
            |--------------------------------------------------------------------------
            */
            $table->foreignId('student_fee_id')
                ->after('fee_payment_id')
                ->constrained('student_fees')
                ->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | Paid Amount Per Fee Head
            |--------------------------------------------------------------------------
            */
            $table->decimal('amount_paid', 10, 2)
                ->after('student_fee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
		Schema::table('fee_payment_items', function (Blueprint $table) {

            $table->dropForeign(['fee_payment_id']);
            $table->dropForeign(['student_fee_id']);

            $table->dropColumn([
                'fee_payment_id',
                'student_fee_id',
                'amount_paid',
            ]);
        });
    }
};
