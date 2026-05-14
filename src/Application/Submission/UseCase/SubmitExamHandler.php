<?php

namespace Testcenter\Application\Submission\UseCase;

use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionRepository;
use Testcenter\Domain\Score;
use Testcenter\Domain\Submission\Event\JustHasNewSubmission;
use Testcenter\Domain\Submission\Submission;
use Testcenter\Domain\Submission\SubmissionRepository;

class SubmitExamHandler
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly SubmissionRepository $submissionRepository,
    ) {
    }

    public function handle(SubmitExamCommand $command): array
    {
        $questions = $this->questionRepository->findByExamId($command->examId);
        $totalScore = $this->score($questions, $command->answers);
        $submission = new Submission(
            userId: $command->userId,
            examId: $command->examId,
            score: new Score($totalScore),
            answers: $command->answers,
        );

        $this->submissionRepository->save($submission);

        event(new JustHasNewSubmission($submission));

        return [
            'exam_id' => $command->examId,
            'user_id' => $command->userId,
            'score' => $totalScore,
        ];
    }

    private function score(array $questions, array $answers): float
    {
        $totalScore = 0;
        /** @var Question $question */
        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id()->value()] ?? null;
            $score = $question->grade($userAnswer);

            $totalScore += $score->score()->value();
        }

        return $totalScore;
    }
}