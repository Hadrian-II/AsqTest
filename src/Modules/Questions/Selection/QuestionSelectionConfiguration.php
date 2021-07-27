<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionSelectionConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionSelectionConfiguration extends AbstractValueObject
{
    const ALL_QUESTIONS = 1;
    const SELECTED_QUESTIONS = 2;
    const RANDOM_QUESTIONS = 3;

    protected ?int $selection_type;

    protected ?int $random_amount;

    public function __construct(
        ?int $selection_type = null,
        ?int $random_amount = null
    ) {
        $this->selection_type = $selection_type;
        $this->random_amount = $random_amount;
    }

    public function getSelectionType() : ?int
    {
        return $this->selection_type;
    }

    public function getRandomAmount() : ?int
    {
        return $this->random_amount;
    }
}