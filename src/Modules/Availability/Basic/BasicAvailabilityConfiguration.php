<?php

namespace srag\asq\Test\Modules\Availability\Basic;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class BasicAvailabilityConfiguration
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class BasicAvailabilityConfiguration extends AbstractValueObject
{
    /**
     * @var ?bool
     */
    protected $visible_if_unavailable;

    /**
     * @param bool $visible_if_unavailable
     * @return BasicAvailabilityConfiguration
     */
    public static function create(?bool $visible_if_unavailable) : BasicAvailabilityConfiguration
    {
        $object = new BasicAvailabilityConfiguration();
        $object->visible_if_unavailable = $visible_if_unavailable;
        return $object;
    }

    /**
     * @return ?bool
     */
    public function isVisibleIfUnavailable() : ?bool
    {
        return $this->visible_if_unavailable;
    }
}