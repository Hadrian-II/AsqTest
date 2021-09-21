<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Availability\Basic;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class BasicAvailabilityConfiguration
 *
 * @package Fluxlabs\Assessment\Test
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