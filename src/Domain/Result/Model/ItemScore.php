<?php
declare(strict_types=1);

namespace srag\asq\Test\Domain\Result\Model;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ItemScore
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag\asq\Test
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class ItemScore extends AbstractValueObject {
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
     * @return ItemScore
     */
    public static function create(int $type, float $max, float $min, float $score) : ItemScore
    {
        $object = new ItemScore();
        $object->scoring_type = $type;
        $object->normal_maximum = $max;
        $object->normal_minimum = $min;
        $object->reached_score = $score;
        return $object;
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