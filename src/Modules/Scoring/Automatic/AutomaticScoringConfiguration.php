<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Scoring\Automatic;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AutomaticScoringConfiguration
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AutomaticScoringConfiguration extends AbstractValueObject
{
    const SCORING_ALL_OR_NOTHING = 'all_or_nothing';
    const SCORING_PARTIAL_ANSWERS = 'partial';

    /**
     * @var ?string
     */
    protected $scoring_mode;

    /**
     * @var ?bool
     */
    protected $allow_negative;

    /**
     * @param string $scoring_mode
     * @param bool $allow_negative
     */
    public function __construct(
        ?string $scoring_mode,
        ?bool $allow_negative
    ) {
        $this->scoring_mode = $scoring_mode;
        $this->allow_negative = $allow_negative;
    }
    /**
     * @return ?string
     */
    public function getScoringMode() : ?string
    {
        return $this->scoring_mode;
    }

    /**
     * @return ?bool
     */
    public function allowNegative() : ?bool
    {
        return $this->allow_negative;
    }
}