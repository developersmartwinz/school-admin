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
		Schema::table('fee_payments', function (Blueprint $table) {

            $table->dropForeign(['student_fee_id']);
            $table->dropColumn([
                'student_fee_id',
                'paid_amount',
            ]);

            $table->foreignId('student_id')
                ->after('id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('total_paid', 10, 2)
                ->after('receipt_no')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
		Schema::table('fee_payments', function (Blueprint $table) {

            $table->dropForeign(['student_id']);
            $table->dropColumn([
                'student_id',
                'total_paid',
            ]);

            $table->foreignId('student_fee_id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('paid_amount', 10, 2);
        });
    }
};
