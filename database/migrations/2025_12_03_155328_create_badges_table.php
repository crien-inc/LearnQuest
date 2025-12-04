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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('icon');
            $table->string('color')->default('#FFD700');
            $table->enum('type', [
                'course_complete',
                'lessons_count',
                'streak',
                'points',
                'first_lesson',
                'perfect_score',
                'special'
            ]);
            $table->integer('requirement_value')->default(1);
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
