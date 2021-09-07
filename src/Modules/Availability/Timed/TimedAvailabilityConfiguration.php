<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Availability\Timed;

use srag\CQRS\Aggregate\AbstractValueObject;
use DateTimeImmutable;

/**
 * Class TimedAvailabilityConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TimedAvailabilityConfiguration extends AbstractValueObject
{
    protected ?DateTimeImmutable $available_from;

    protected ?DateTimeImmutable $available_to;

    public function __construct(
        ?DateTimeImmutable $available_from = null,
        ?DateTimeImmutable $available_to = null
    ) {
        $this->available_from = $available_from;
        $this->available_to = $available_to;
    }

    public function getAvailableFrom() : ?DateTimeImmutable
    {
        return $this->available_from;
    }

    public function getAvailableTo() : ?DateTimeImmutable
    {
        return $this->available_to;
    }
}