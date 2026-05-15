<?php

namespace Testcenter\Domain\Question\Type;

use Testcenter\Domain\Question\MatchingPairs;
use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\QuestionType;
use Testcenter\Domain\Score;
use Testcenter\Domain\Submission\Answer\Answer;
use Testcenter\Domain\Submission\Answer\MatchingAnswer;
use Testcenter\Domain\Submission\GradeResult;

class MatchingQuestion extends Question
{
    public function __construct(
        QuestionID $id,
        QuestionText $text,
        Score $score,
        private readonly MatchingPairs $pairs,
    ) {
        parent::__construct($id, QuestionType::MATCHING, $text, $score);
    }

    public function grade(Answer $answer): GradeResult
    {
        if (!$answer instanceof MatchingAnswer) {
            throw new \InvalidArgumentException('Invalid answer type');
        }

        $correct = 0;
        foreach ($answer->value() as $left => $right) {
            if (
                $this->pairs->isCorrect($left, $right)
            ) {
                $correct++;
            }
        }

        if ($this->pairs->total() === 0) {
            return new GradeResult(true, $this->score());
        }

        return new GradeResult(
            ($correct / $this->pairs->total()) >= 0.5,
            new Score(($correct / $this->pairs->total()) * $this->score->value())
        );
    }
}