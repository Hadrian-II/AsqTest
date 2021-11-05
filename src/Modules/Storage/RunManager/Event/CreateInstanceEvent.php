<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\RunManager\Event;

use Fluxlabs\Assessment\Tools\Event\Event;
use Fluxlabs\Assessment\Tools\Event\IEventUser;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class CreateInstanceEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CreateInstanceEvent extends Event
{
    //Event contains no data
}