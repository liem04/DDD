<?php

namespace Testcenter\Application\Submission\UseCase;

use InvalidArgumentException;
use Testcenter\Domain\Exam\ExamRepository;
use Testcenter\Domain\Exam\Exception\ExamNotFoundException;
use Testcenter\Domain\Question\Question;
use Testcenter\Domain\Question\QuestionRepository;
use Testcenter\Domain\Question\QuestionType;
use Testcenter\Domain\Shared\DomainEventPublisher;
use Testcenter\Domain\Submission\Answer\Answer;
use Testcenter\Domain\Submission\Answer\FillBlankAnswer;
use Testcenter\Domain\Submission\Answer\MatchingAnswer;
use Testcenter\Domain\Submission\Answer\SingleChoiceAnswer;
use Testcenter\Domain\Submission\Answer\TrueFalseAnswer;
use Testcenter\Domain\Submission\Submission;
use Testcenter\Domain\Submission\SubmissionRepository;

class SubmitExamHandler
{
    public function __construct(
        private readonly ExamRepository $examRepository,
        private readonly QuestionRepository $questionRepository,
        private readonly SubmissionRepository $submissionRepository,
        private readonly DomainEventPublisher $publisher,
    ) {
    }

    /**
     * @throws ExamNotFoundException
     */
    public function handle(SubmitExamCommand $command): Submission
    {
        $exam = $this->examRepository->findById($command->examId);
        if (!$exam->isActive()) {
            throw new InvalidArgumentException('Exam is not active');
        }

        $questions = $this->questionRepository->findByIds(array_keys($command->answers));
        $answers = $this->makeAnswers($questions, $command->answers);

        $submission = Submission::submit(
            userId: $command->userId,
            examId: $command->examId,
            questions: $questions,
            answers: $answers,
        );

        $this->submissionRepository->save($submission);

        $this->publisher->publish(...$submission->releaseEvents());

        return $submission;
    }

    private function makeAnswers(array $questions, array $userAnswers): array
    {
        $result = [];
        foreach ($userAnswers as $questionId => $userAnswer) {
            $result[$questionId] = $this->buildAnswer(
                question: $questions[$questionId],
                userAnswer: $userAnswer,
            );
        }

        return $result;
    }

    private function buildAnswer(Question $question, mixed $userAnswer): Answer
    {
        return match ($question->type()) {
            QuestionType::TRUE_FALSE => new TrueFalseAnswer($userAnswer),
            QuestionType::SINGLE_CHOICE => new SingleChoiceAnswer($userAnswer),
            QuestionType::FILL_BLANK => new FillBlankAnswer($userAnswer),
            QuestionType::MATCHING => new MatchingAnswer($userAnswer),
            default => throw new InvalidArgumentException('Unsupported question type'),
        };
    }
}
