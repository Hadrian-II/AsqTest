<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Availability\Basic;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class BasicAvailabilityConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class BasicAvailabilityConfiguration extends AbstractValueObject
{
    protected ?bool $visible_if_unavailable;

    public function __construct(?bool $visible_if_unavailable = null)
    {
        $this->visible_if_unavailable = $visible_if_unavailable;
    }

    public function isVisibleIfUnavailable() : ?bool
    {
        return $this->visible_if_unavailable;
    }
}