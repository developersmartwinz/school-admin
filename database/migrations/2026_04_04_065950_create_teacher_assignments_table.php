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
        Schema::create('teacher_assignments', function (Blueprint $table) {
			$table->id();
			$table->foreignId('teacher_id')->constrained()->onDelete('cascade');
			$table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
			$table->foreignId('section_id')->constrained()->onDelete('cascade');
			$table->string('subject');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
