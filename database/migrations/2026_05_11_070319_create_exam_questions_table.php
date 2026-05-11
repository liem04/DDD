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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('exam_id');

            $table->unsignedInteger('question_id');

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique([
                'exam_id',
                'question_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
