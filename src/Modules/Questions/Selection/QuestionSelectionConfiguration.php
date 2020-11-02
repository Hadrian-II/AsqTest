<?php

namespace srag\asq\Test\Modules\Questions\Selection;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionSelectionConfiguration
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionSelectionConfiguration extends AbstractValueObject
{
    const ALL_QUESTIONS = 'all_questions';
    const SELECTED_QUESTIONS = 'selected_questions';
    const RANDOM_QUESTIONS = 'random_questions';
    const RANDOM_POINTS = 'random_points';

    /**
     * @var ?string
     */
    protected $selection_type;

    /**
     * @var ?int
     */
    protected $random_amount;

    /**
     * @param string $selection_type
     * @param int $random_amount
     * @return QuestionSelectionConfiguration
     */
    public static function create(
        ?string $selection_type,
        ?int $random_amount
        ) : QuestionSelectionConfiguration
    {
        $object = new QuestionSelectionConfiguration();
        $object->selection_type = $selection_type;
        $object->random_amount = $random_amount;
        return $object;
    }
    /**
     * @return ?string
     */
    public function getSelectionType() : ?string
    {
        return $this->selection_type;
    }

    /**
     * @return ?int
     */
    public function getRandomAmount() : ?int
    {
        return $this->random_amount;
    }
}