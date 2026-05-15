<?php

namespace Testcenter\Domain\Question\Type;

use Testcenter\Domain\Question\AcceptedAnswers;
use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\QuestionType;
use Testcenter\Domain\Score;
use Testcenter\Domain\Submission\Answer\Answer;
use Testcenter\Domain\Submission\Answer\FillBlankAnswer;
use Testcenter\Domain\Submission\GradeResult;

class FillBlankQuestion extends Question
{
    public function __construct(
        QuestionID $id,
        QuestionText $text,
        Score $score,
        private readonly AcceptedAnswers $acceptedAnswers
    ) {
        parent::__construct($id, QuestionType::FILL_BLANK, $text, $score);
    }

    public function grade(Answer $answer): GradeResult
    {
        if (!$answer instanceof FillBlankAnswer) {
            throw new \InvalidArgumentException('Invalid answer type');
        }

        if ($this->acceptedAnswers->contains($answer->value())) {
            return new GradeResult(true, $this->score());
        }

        return new GradeResult(false, new Score(0));
    }
}