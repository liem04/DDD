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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('exam_id');
            $table->unsignedInteger('user_id');

            $table->unsignedInteger('score')->default(0);

            $table->enum('status', [
                'in_progress',
                'submitted',
                'graded'
            ])->default('submitted');

            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->index([
                'exam_id',
                'user_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
