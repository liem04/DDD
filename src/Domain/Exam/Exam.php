<?php

namespace Testcenter\Domain\Exam;

use Testcenter\Domain\Exam\Exception\ExamCannotPublishException;

class Exam
{
    public function __construct(
        private readonly ExamID $id,
        private ExamStatus $examStatus,
        private Title $title,
        private Description $description,
    ) {
    }

    public function id(): ExamID
    {
        return $this->id;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function rename(Title $newTitle): void
    {
        if ($this->title->value() === $newTitle->value()) {
            return;
        }

        $this->title = $newTitle;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function updateDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function isActive(): bool
    {
        return $this->examStatus === ExamStatus::ACTIVE;
    }

    /**
     * @throws ExamCannotPublishException
     */
    public function publish(): void
    {
        if ($this->examStatus === ExamStatus::ACTIVE) {
            throw new ExamCannotPublishException(
                'Exam is already published'
            );
        }

        $this->examStatus = ExamStatus::ACTIVE;
    }
}