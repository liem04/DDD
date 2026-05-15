<?php

namespace Testcenter\Infrastructure\Submission;

use App\Models\SubmissionAnswer;
use Illuminate\Support\Facades\DB;
use Testcenter\Domain\Submission\Submission;
use Testcenter\Domain\Submission\SubmissionRepository;

class MysqlSubmissionRepository implements SubmissionRepository
{
    public function save(Submission $submission): Submission
    {
        return DB::transaction(function () use ($submission) {
            $submissionModel = \App\Models\Submission::query()
                ->create([
                    'user_id' => $submission->getUserId()->value(),
                    'exam_id' => $submission->getExamId()->value(),
                    'score' => $submission->getScoreResult()->total(),
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);
            foreach ($submission->getAnswers()->all() as $questionId => $answer) {
                SubmissionAnswer::query()
                    ->create([
                        'submission_id' => $submissionModel->id,
                        'question_id' => $questionId,
                        'answer' => $this->normalizeAnswer($answer->value()),
                        'score' => $submission->getScoreResult()->answerScores()[$questionId]->score()->value(),
                    ]);
            }

            return $submission;
        });
    }

    private function normalizeAnswer(mixed $answer): mixed
    {
        if (is_array($answer) || is_object($answer)) {
            return json_encode($answer, JSON_UNESCAPED_UNICODE);
        }

        return $answer;
    }
}