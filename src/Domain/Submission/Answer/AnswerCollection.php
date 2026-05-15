<?php

namespace Testcenter\Domain\Submission\Answer;

use ArrayIterator;
use Countable;
use Testcenter\Domain\Question\QuestionID;

class AnswerCollection implements Countable, \IteratorAggregate
{
    /**
     * @var Answer[]
     */
    private array $answers = [];

    /**
     * @param Answer[] $answers
     */
    public function __construct(array $answers)
    {
        foreach ($answers as $questionId => $answer) {
            $this->add($questionId, $answer);
        }
    }

    public function add(int $questionId, Answer $answer): void
    {
        if (isset($this->answers[$questionId])) {
            throw new \LogicException(
                "Duplicate answer for question {$questionId}"
            );
        }

        $this->answers[$questionId] = $answer;
    }

    public function findByQuestionId(QuestionID $questionId): ?Answer
    {
        return $this->answers[$questionId->value()] ?? null;
    }

    public function contains(QuestionID $questionId): bool
    {
        return isset($this->answers[$questionId->value()]);
    }

    /**
     * @return Answer[]
     */
    public function all(): array
    {
        return $this->answers;
    }

    public function count(): int
    {
        return count($this->answers);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }
}
