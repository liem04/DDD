<?php

namespace Testcenter\Domain\Submission\Answer;

class OrderingAnswer implements Answer
{
    public function __construct(
        private readonly array $orderedItems,
    ) {
    }

    public function value(): array
    {
        return $this->orderedItems;
    }
}
