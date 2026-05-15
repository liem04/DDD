<?php

namespace Testcenter\Domain\Question\Type;

use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\QuestionType;
use Testcenter\Domain\Score;
use Testcenter\Domain\Submission\Answer\Answer;
use Testcenter\Domain\Submission\Answer\TrueFalseAnswer;
use Testcenter\Domain\Submission\GradeResult;

class TrueFalseQuestion extends Question
{
    public function __construct(
        QuestionID $id,
        QuestionText $text,
        Score $score,
        private readonly bool $correct,
    ) {
        parent::__construct($id, QuestionType::TRUE_FALSE, $text, $score);
    }

    public function grade(Answer $answer): GradeResult
    {
        if (!$answer instanceof TrueFalseAnswer) {
            throw new \InvalidArgumentException('Invalid answer type');
        }

        return $answer->value() === $this->correct
            ? new GradeResult(true, $this->score())
            : new GradeResult(false, new Score(0));
    }
}