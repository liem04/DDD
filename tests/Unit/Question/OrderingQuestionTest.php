<?php

namespace Tests\Unit\Question;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\Type\OrderingQuestion;
use Testcenter\Domain\Shared\Score;
use Testcenter\Domain\Submission\Answer\OrderingAnswer;
use Testcenter\Domain\Submission\Answer\TrueFalseAnswer;

class OrderingQuestionTest extends TestCase
{
    public function test_it_returns_full_score_when_order_is_correct(): void
    {
        $question = new OrderingQuestion(
            id: new QuestionID(1),
            text: new QuestionText('Order the steps'),
            score: new Score(9.0),
            correctOrder: ['Plan', 'Code', 'Test'],
        );

        $result = $question->grade(new OrderingAnswer(['Plan', 'Code', 'Test']));

        $this->assertTrue($result->isCorrect());
        $this->assertEquals(9.0, $result->score()->value());
    }

    public function test_it_returns_partial_score_for_correct_positions(): void
    {
        $question = new OrderingQuestion(
            id: new QuestionID(1),
            text: new QuestionText('Order the steps'),
            score: new Score(9),
            correctOrder: ['Plan', 'Code', 'Test'],
        );

        $result = $question->grade(new OrderingAnswer(['Plan', 'Test', 'Code']));

        $this->assertFalse($result->isCorrect());
        $this->assertEquals(3.0, $result->score()->value());
    }

    public function test_it_throws_exception_when_answer_type_is_invalid(): void
    {
        $question = new OrderingQuestion(
            id: new QuestionID(1),
            text: new QuestionText('Order the steps'),
            score: new Score(10),
            correctOrder: ['Plan', 'Code'],
        );

        $this->expectException(InvalidArgumentException::class);

        $question->grade(new TrueFalseAnswer(true));
    }
}
