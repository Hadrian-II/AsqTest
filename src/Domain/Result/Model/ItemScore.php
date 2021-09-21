<?php
declare(strict_types=1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Model;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ItemScore
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ItemScore extends AbstractValueObject
{
    const AUTOMATIC_SCORING = 1;
    const MANUAL_SCORING = 2;

    protected ?int $scoring_type;

    protected ?float $normal_maximum;

    protected ?float $normal_minimum;

    protected ?float $reached_score;

    public function __construct(int $type = null, float $max = null, float $min = null, float $score = null)
    {
        $this->scoring_type = $type;
        $this->normal_maximum = $max;
        $this->normal_minimum = $min;
        $this->reached_score = $score;
    }

    public function getScoringType() : ?int
    {
        return $this->scoring_type;
    }

    public function getNormalMaximum() : ?float
    {
        return $this->normal_maximum;
    }

    public function getNormalMinimum() : ?float
    {
        return $this->normal_minimum;
    }

    public function getReachedScore() : ?float
    {
        return $this->reached_score;
    }
}
