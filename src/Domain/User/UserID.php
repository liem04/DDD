<?php

namespace Testcenter\Domain\User;

class UserID
{
    public function __construct(
        private readonly int $id,
    ) {
    }

    public function value(): int
    {
        return $this->id;
    }
}