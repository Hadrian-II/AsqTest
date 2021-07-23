<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Scoring\Automatic;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AutomaticScoringConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AutomaticScoringConfiguration extends AbstractValueObject
{
    const SCORING_ALL_OR_NOTHING = 1;
    const SCORING_PARTIAL_RESULTS = 2;

    /**
     * @var ?string
     */
    protected $scoring_mode;

    /**
     * @var ?bool
     */
    protected $allow_negative;

    /**
     * @param int $scoring_mode
     * @param bool $allow_negative
     */
    public function __construct(
        ?int $scoring_mode = null,
        ?bool $allow_negative = null
    ) {
        $this->scoring_mode = $scoring_mode;
        $this->allow_negative = $allow_negative;
    }
    /**
     * @return ?string
     */
    public function getScoringMode() : ?int
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