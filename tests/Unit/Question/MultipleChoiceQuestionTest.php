<?php

namespace Tests\Unit\Question;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Testcenter\Domain\Question\OptionCollection;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\Type\MultipleChoiceQuestion;
use Testcenter\Domain\Shared\Score;
use Testcenter\Domain\Submission\Answer\MultipleChoiceAnswer;
use Testcenter\Domain\Submission\Answer\TrueFalseAnswer;

class MultipleChoiceQuestionTest extends TestCase
{
    public function test_it_returns_full_score_when_answers_are_correct(): void
    {
        $question = new MultipleChoiceQuestion(
            id: new QuestionID(1),
            text: new QuestionText('Which are programming languages?'),
            score: new Score(10.0),
            options: new OptionCollection([
                'A' => 'PHP',
                'B' => 'HTML',
                'C' => 'JavaScript',
                'D' => 'CSS',
            ]),
            correct: ['A', 'C'],
        );

        $result = $question->grade(new MultipleChoiceAnswer(['C', 'A']));

        $this->assertTrue($result->isCorrect());
        $this->assertEquals(10.0, $result->score()->value());
    }

    public function test_it_returns_zero_score_when_answers_do_not_match_exactly(): void
    {
        $question = new MultipleChoiceQuestion(
            id: new QuestionID(1),
            text: new QuestionText('Which are programming languages?'),
            score: new Score(10),
            options: new OptionCollection([
                'A' => 'PHP',
                'B' => 'HTML',
                'C' => 'JavaScript',
            ]),
            correct: ['A', 'C'],
        );

        $result = $question->grade(new MultipleChoiceAnswer(['A']));

        $this->assertFalse($result->isCorrect());
        $this->assertEquals(0, $result->score()->value());
    }

    public function test_it_throws_exception_when_answer_type_is_invalid(): void
    {
        $question = new MultipleChoiceQuestion(
            id: new QuestionID(1),
            text: new QuestionText('Which are programming languages?'),
            score: new Score(10),
            options: new OptionCollection([
                'A' => 'PHP',
                'C' => 'JavaScript',
            ]),
            correct: ['A', 'C'],
        );

        $this->expectException(InvalidArgumentException::class);

        $question->grade(new TrueFalseAnswer(true));
    }
}
