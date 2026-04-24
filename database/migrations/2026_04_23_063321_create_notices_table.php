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
         Schema::create('notices', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Notice Info
            |--------------------------------------------------------------------------
            */
            $table->string('title');
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Notice Date & Time
            |--------------------------------------------------------------------------
            */
            $table->date('notice_date');
            $table->time('notice_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Sent By
            |--------------------------------------------------------------------------
            */
            $table->string('sent_by')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Attachment (PDF / Image / Doc)
            |--------------------------------------------------------------------------
            */
            $table->string('attachment')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            /*
            |--------------------------------------------------------------------------
            | Created By (Admin User)
            |--------------------------------------------------------------------------
            */
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
