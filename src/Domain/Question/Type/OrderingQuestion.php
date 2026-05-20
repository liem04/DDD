<?php

namespace Testcenter\Domain\Question\Type;

use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\QuestionType;
use Testcenter\Domain\Shared\Score;
use Testcenter\Domain\Submission\Answer\Answer;
use Testcenter\Domain\Submission\Answer\OrderingAnswer;
use Testcenter\Domain\Submission\GradeResult;

class OrderingQuestion extends Question
{
    public function __construct(
        QuestionID $id,
        QuestionText $text,
        Score $score,
        private readonly array $correctOrder,
    ) {
        if (empty($correctOrder)) {
            throw new \InvalidArgumentException('Correct order cannot be empty');
        }

        parent::__construct($id, QuestionType::ORDERING, $text, $score);
    }

    public function grade(Answer $answer): GradeResult
    {
        if (!$answer instanceof OrderingAnswer) {
            throw new \InvalidArgumentException('Invalid answer type');
        }

        $correct = 0;
        foreach (array_values($answer->value()) as $index => $item) {
            if (isset($this->correctOrder[$index]) && $this->correctOrder[$index] === $item) {
                $correct++;
            }
        }

        $total = count($this->correctOrder);
        $earnedScore = ($correct / $total) * $this->score()->value();

        return new GradeResult(
            $correct === $total,
            new Score($earnedScore)
        );
    }

    public function correctOrder(): array
    {
        return $this->correctOrder;
    }
}
