<?php

namespace srag\asq\Test\Modules\Availability\Timed;

use srag\CQRS\Aggregate\AbstractValueObject;
use ilDateTime;

/**
 * Class TimedAvailabilityConfiguration
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TimedAvailabilityConfiguration extends AbstractValueObject
{
    /**
     * @var ?ilDateTime
     */
    protected $available_from;

    /**
     * @var ?ilDateTime
     */
    protected $available_to;

    /**
     * @param ilDateTime $available_from
     * @param ilDateTime $available_to
     * @return TimedAvailabilityConfiguration
     */
    public static function create(
        ?ilDateTime $available_from,
        ?ilDateTime $available_to
        ) : TimedAvailabilityConfiguration
    {
        $object = new TimedAvailabilityConfiguration();
        $object->available_from = $available_from;
        $object->available_to = $available_to;
        return $object;
    }
    /**
     * @return ?ilDateTime
     */
    public function getAvailableFrom() : ?ilDateTime
    {
        return $this->available_from;
    }

    /**
     * @return ?ilDateTime
     */
    public function getAvailableTo() : ?ilDateTime
    {
        return $this->available_to;
    }
}