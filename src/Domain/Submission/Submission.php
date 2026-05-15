<?php

namespace Testcenter\Domain\Submission;

use LogicException;
use Testcenter\Domain\Exam\ExamID;
use Testcenter\Domain\Question\QuestionCollection;
use Testcenter\Domain\Shared\AggregateRoot;
use Testcenter\Domain\Submission\Answer\AnswerCollection;
use Testcenter\Domain\User\UserID;

class Submission extends AggregateRoot
{
    private bool $scored = false;
    private ScoreResult $scoreResult;

    public function __construct(
        private readonly UserID $userId,
        private readonly ExamID $examId,
        private readonly QuestionCollection $questions,
        private readonly AnswerCollection $answers
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

    public function getQuestions(): QuestionCollection
    {
        return $this->questions;
    }

    public function getAnswers(): AnswerCollection
    {
        return $this->answers;
    }

    public static function submit(
        int $userId,
        int $examId,
        array $questions,
        array $answers
    ): self {
        $submission = new self(
            userId: new UserID($userId),
            examId: new ExamID($examId),
            questions: new QuestionCollection($questions),
            answers: new AnswerCollection($answers)
        );
        $submission->calculateScore();

        $submission->recordEvent(new Event\JustHasNewSubmission($submission));

        return $submission;
    }

    private function calculateScore(): ScoreResult
    {
        $totalScore = 0;
        $answerScores = [];
        foreach ($this->questions as $question) {
            $userAnswer = $this->answers->findByQuestionId($question->id());
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
            throw new LogicException('Submission has not been scored yet.');
        }

        return $this->scoreResult;
    }
}
