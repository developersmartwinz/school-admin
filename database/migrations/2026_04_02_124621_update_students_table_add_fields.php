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
		Schema::table('students', function (Blueprint $table) {
			$table->string('reg_no')->nullable();
			$table->date('dob')->nullable();
			$table->string('gender')->nullable();
			$table->string('house')->nullable();
			$table->string('category')->nullable();
			$table->text('address')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('blood_group')->nullable();
			$table->string('aadhar')->nullable();
			
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
