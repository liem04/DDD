<?php

namespace Testcenter\Domain\Question;

enum QuestionType: string
{
    case TRUE_FALSE = 'true_false';
    case SINGLE_CHOICE = 'single_choice';
    case MULTIPLE_CHOICE = 'multiple_choice';
    case FILL_BLANK = 'fill_blank';
    case MATCHING = 'matching';
    case ORDERING = 'ordering';
}
