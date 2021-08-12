<?php
declare(strict_types = 1);

namespace srag\asq\Test\Lib\Event\Standard;

use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventUser;

/**
 * Class RemoveObjectEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RemoveObjectEvent extends Event
{
    public function __construct(IEventUser $sender, ITestObject $object)
    {
        parent::__construct($sender, $object);
    }
}