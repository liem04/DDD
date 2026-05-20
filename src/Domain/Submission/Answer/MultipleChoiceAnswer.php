<?php

namespace Testcenter\Domain\Submission\Answer;

class MultipleChoiceAnswer implements Answer
{
    public function __construct(
        private readonly array $values,
    ) {
    }

    public function value(): array
    {
        return $this->values;
    }
}
