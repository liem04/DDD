<?php

namespace Testcenter\Infrastructure\Question;

use Testcenter\Domain\Question\AcceptedAnswers;
use Testcenter\Domain\Question\MatchingPairs;
use Testcenter\Domain\Question\OptionCollection;
use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionID;
use Testcenter\Domain\Question\QuestionText;
use Testcenter\Domain\Question\QuestionType;
use Testcenter\Domain\Question\Type\FillBlankQuestion;
use Testcenter\Domain\Question\Type\MatchingQuestion;
use Testcenter\Domain\Question\Type\MultipleChoiceQuestion;
use Testcenter\Domain\Question\Type\OrderingQuestion;
use Testcenter\Domain\Question\Type\SingleChoiceQuestion;
use Testcenter\Domain\Question\Type\TrueFalseQuestion;
use Testcenter\Domain\Shared\Score;

class QuestionMapper
{
    public function toDomain(\App\Models\Question $questionEloquent): Question
    {
        return match (QuestionType::from($questionEloquent->type)) {
            QuestionType::TRUE_FALSE =>
            new TrueFalseQuestion(
                id: new QuestionID($questionEloquent->id),
                text: new QuestionText($questionEloquent->content),
                score: new Score($questionEloquent->score),
                correct: $questionEloquent->payload['correct'],
            ),
            QuestionType::SINGLE_CHOICE =>
            new SingleChoiceQuestion(
                id: new QuestionID($questionEloquent->id),
                text: new QuestionText($questionEloquent->content),
                score: new Score($questionEloquent->score),
                options: new OptionCollection($questionEloquent->payload['options']),
                correct: $questionEloquent->payload['correct']
            ),
            QuestionType::MULTIPLE_CHOICE =>
            new MultipleChoiceQuestion(
                id: new QuestionID($questionEloquent->id),
                text: new QuestionText($questionEloquent->content),
                score: new Score($questionEloquent->score),
                options: new OptionCollection($questionEloquent->payload['options']),
                correct: $questionEloquent->payload['correct']
            ),
            QuestionType::FILL_BLANK =>
            new FillBlankQuestion(
                id: new QuestionID($questionEloquent->id),
                text: new QuestionText($questionEloquent->content),
                score: new Score($questionEloquent->score),
                acceptedAnswers: new AcceptedAnswers($questionEloquent->payload['answers']),
            ),
            QuestionType::MATCHING =>
            new MatchingQuestion(
                id: new QuestionID($questionEloquent->id),
                text: new QuestionText($questionEloquent->content),
                score: new Score($questionEloquent->score),
                pairs: new MatchingPairs($questionEloquent->payload['pairs']),
            ),
            QuestionType::ORDERING =>
            new OrderingQuestion(
                id: new QuestionID($questionEloquent->id),
                text: new QuestionText($questionEloquent->content),
                score: new Score($questionEloquent->score),
                correctOrder: $questionEloquent->payload['correct_order'],
            ),
            default => throw new \Exception('Unsupported question type: ' . $questionEloquent->type),
        };
    }
}
