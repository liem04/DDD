<?php

namespace Testcenter\Domain\Question\Type;

use Testcenter\Domain\Question\OptionCollection;
use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\QuestionType;
use Testcenter\Domain\Shared\Score;
use Testcenter\Domain\Submission\Answer\Answer;
use Testcenter\Domain\Submission\Answer\MultipleChoiceAnswer;
use Testcenter\Domain\Submission\GradeResult;

class MultipleChoiceQuestion extends Question
{
    public function __construct(
        QuestionID $id,
        QuestionText $text,
        Score $score,
        private readonly OptionCollection $options,
        private readonly array $correct,
    ) {
        if (empty($correct)) {
            throw new \InvalidArgumentException('Correct answers cannot be empty');
        }

        parent::__construct($id, QuestionType::MULTIPLE_CHOICE, $text, $score);
    }

    public function grade(Answer $answer): GradeResult
    {
        if (!$answer instanceof MultipleChoiceAnswer) {
            throw new \InvalidArgumentException('Invalid answer type');
        }

        return $this->normalize($answer->value()) === $this->normalize($this->correct)
            ? new GradeResult(true, $this->score())
            : new GradeResult(false, new Score(0));
    }

    public function options(): OptionCollection
    {
        return $this->options;
    }

    private function normalize(array $answers): array
    {
        $answers = array_values(array_unique($answers));
        sort($answers);

        return $answers;
    }
}
