<?php

namespace Testcenter\Domain\Submission;

class ScoreResult
{
    public function __construct(
        private readonly float $total,
        private readonly array $answerScores,
    ) {
    }

    public function total(): float
    {
        return $this->total;
    }

    public function answerScores(): array
    {
        return $this->answerScores;
    }
}