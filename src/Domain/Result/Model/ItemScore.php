<?php
declare(strict_types=1);

namespace srag\asq\Test\Domain\Result\Model;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ItemScore
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ItemScore extends AbstractValueObject
{
    const AUTOMATIC_SCORING = 1;
    const MANUAL_SCORING = 2;

    /**
     * @var int
     */
    protected $scoring_type;

    /**
     * @var float
     */
    protected $normal_maximum;

    /**
     * @var float
     */
    protected $normal_minimum;

    /**
     * @var float
     */
    protected $reached_score;

    /**
     * @param int $type
     * @param float $max
     * @param float $min
     * @param float $score
     */
    public function __construct(int $type = null, float $max = null, float $min = null, float $score = null)
    {
        $this->scoring_type = $type;
        $this->normal_maximum = $max;
        $this->normal_minimum = $min;
        $this->reached_score = $score;
    }

    /**
     * @return int
     */
    public function getScoringType()
    {
        return $this->scoring_type;
    }

    /**
     * @return int
     */
    public function getNormalMaximum()
    {
        return $this->normal_maximum;
    }

    /**
     * @return int
     */
    public function getNormalMinimum()
    {
        return $this->normal_minimum;
    }

    /**
     * @return int
     */
    public function getReachedScore()
    {
        return $this->reached_score;
    }
}
