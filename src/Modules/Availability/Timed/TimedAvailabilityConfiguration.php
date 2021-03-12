<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Availability\Timed;

use srag\CQRS\Aggregate\AbstractValueObject;
use DateTimeImmutable;

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
     * @param DateTimeImmutable $available_from
     * @param DateTimeImmutable $available_to
     */
    public function __construct(
        ?DateTimeImmutable $available_from = null,
        ?DateTimeImmutable $available_to = null
    ) {
        $this->available_from = $available_from;
        $this->available_to = $available_to;
    }
    /**
     * @return ?ilDateTime
     */
    public function getAvailableFrom() : ?DateTimeImmutable
    {
        return $this->available_from;
    }

    /**
     * @return ?ilDateTime
     */
    public function getAvailableTo() : ?DateTimeImmutable
    {
        return $this->available_to;
    }
}