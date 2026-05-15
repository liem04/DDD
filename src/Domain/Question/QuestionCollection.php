<?php

namespace Testcenter\Domain\Question;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Testcenter\Domain\Question\Exception\QuestionException;

class QuestionCollection implements Countable, IteratorAggregate
{
    /**
     * @var Question[]
     */
    private array $questions = [];

    /**
     * @param Question[] $questions
     * @throws QuestionException
     */
    public function __construct(array $questions)
    {
        foreach ($questions as $question) {
            $this->add($question);
        }
    }

    /**
     * @throws QuestionException
     */
    public function add(Question $question): void
    {
        $id = $question->id()->value();
        if (isset($this->questions[$id])) {
            throw new QuestionException(
                "Duplicate question id: {$id}"
            );
        }

        $this->questions[$id] = $question;
    }

    public function findById(QuestionID $id): ?Question
    {
        return $this->questions[$id->value()] ?? null;
    }

    public function contains(QuestionID $id): bool
    {
        return isset(
            $this->questions[$id->value()]
        );
    }

    /**
     * @return Question[]
     */
    public function all(): array
    {
        return array_values($this->questions);
    }

    public function count(): int
    {
        return count($this->questions);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }
}
