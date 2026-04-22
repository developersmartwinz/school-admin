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
         Schema::create('fee_types', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // Tuition Fee
            $table->decimal('amount', 10, 2)->default(0);

            $table->enum('frequency', [
                'monthly',
                'quarterly',
                'half_yearly',
                'yearly',
                'one_time'
            ])->default('monthly');

            $table->boolean('is_optional')->default(false);

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_types');
    }
};
