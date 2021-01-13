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
     */
    public function __construct(
        ?ilDateTime $available_from,
        ?ilDateTime $available_to
        )
    {
        $this->available_from = $available_from;
        $this->available_to = $available_to;
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