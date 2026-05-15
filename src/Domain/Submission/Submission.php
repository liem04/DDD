<?php

namespace Testcenter\Domain\Submission;

use Testcenter\Domain\Exam\ExamID;
use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Submission\Answer\Answer;
use Testcenter\Domain\User\UserID;

class Submission
{
    private bool $scored = false;
    private ScoreResult $scoreResult;

    public function __construct(
        private readonly UserID $userId,
        private readonly ExamID $examId,
        /** @var Question[] $questions */
        private array $questions,
        /** @var Answer[] $answers */
        private readonly array $answers
    ) {
    }

    public function getUserId(): UserID
    {
        return $this->userId;
    }

    public function getExamId(): ExamID
    {
        return $this->examId;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function hasBeenScored(): bool
    {
        return $this->scored;
    }

    public function score(): ScoreResult
    {
        $totalScore = 0;
        $answerScores = [];
        foreach ($this->questions as $question) {
            $userAnswer = $this->answers[$question->id()->value()] ?? null;
            $score = $question->grade($userAnswer);
            $answerScores[$question->id()->value()] = $score;
            $totalScore += $score->score()->value();
        }

        $this->scored = true;
        $this->scoreResult = new ScoreResult($totalScore, $answerScores);

        return $this->scoreResult;
    }

    public function getScoreResult(): ScoreResult
    {
        if (!$this->scored) {
            throw new \LogicException('Submission has not been scored yet.');
        }

        return $this->scoreResult;
    }
}