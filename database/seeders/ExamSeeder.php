<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('exams')->insert([
            [
                'id' => 1,
                'title' => 'PHP Backend Basic Test',
                'description' => 'Bài test cơ bản dành cho backend developer.',
                'duration_minutes' => 30,
                'started_at' => now(),
                'ended_at' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('exam_questions')->insert([
            [
                'exam_id' => 1,
                'question_id' => 1,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'exam_id' => 1,
                'question_id' => 2,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'exam_id' => 1,
                'question_id' => 3,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'exam_id' => 1,
                'question_id' => 4,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
