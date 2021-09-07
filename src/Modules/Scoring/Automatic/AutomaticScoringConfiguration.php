<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Scoring\Automatic;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AutomaticScoringConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AutomaticScoringConfiguration extends AbstractValueObject
{
    const SCORING_ALL_OR_NOTHING = 1;
    const SCORING_PARTIAL_RESULTS = 2;

    protected ?string $scoring_mode;

    protected ?bool $allow_negative;

    public function __construct(
        ?int $scoring_mode = null,
        ?bool $allow_negative = null
    ) {
        $this->scoring_mode = $scoring_mode;
        $this->allow_negative = $allow_negative;
    }

    public function getScoringMode() : ?int
    {
        return $this->scoring_mode;
    }

    public function allowNegative() : ?bool
    {
        return $this->allow_negative;
    }
}