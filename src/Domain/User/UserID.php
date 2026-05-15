<?php

namespace Testcenter\Domain\User;

class UserID
{
    public function __construct(
        private readonly int $id,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }
}