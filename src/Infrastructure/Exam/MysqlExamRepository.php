<?php

namespace Testcenter\Infrastructure\Exam;

use Testcenter\Domain\Exam\Description;
use Testcenter\Domain\Exam\Exam;
use Testcenter\Domain\Exam\ExamID;
use Testcenter\Domain\Exam\ExamRepository;
use Testcenter\Domain\Exam\Title;

class MysqlExamRepository implements ExamRepository
{
    public function findById(int $id): Exam
    {
        $examEloquent = \App\Models\Exam::find($id);
        return new Exam(
            id: new ExamID($examEloquent->id),
            examStatus: $examEloquent->exam_status,
            title: new Title($examEloquent->title),
            description: new Description($examEloquent->description),
        );
    }
}