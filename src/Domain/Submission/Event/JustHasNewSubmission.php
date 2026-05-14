<?php

namespace Testcenter\Domain\Submission\Event;

use Testcenter\Domain\Submission\Submission;

class JustHasNewSubmission
{
    public function __construct(
        private readonly Submission $submission
    ) {
    }

    public function getSubmission(): Submission
    {
        return $this->submission;
    }
}