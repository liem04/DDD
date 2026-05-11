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
        Schema::create('submission_answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('submission_id');

            $table->unsignedInteger('question_id');

            $table->json('answer')->nullable();

            $table->unsignedInteger('score')->default(0);

            $table->timestamps();

            $table->unique([
                'submission_id',
                'question_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_answers');
    }
};
