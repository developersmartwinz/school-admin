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
        Schema::create('time_slots', function (Blueprint $table) {
			$table->id();

			$table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');

			$table->time('start_time');
			$table->time('end_time');

			$table->enum('type', ['class', 'break']); // ⭐ KEY

			$table->integer('order')->default(1);

			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
