<?php
declare(strict_types = 1);

namespace srag\asq\Test\Lib\Event\Standard;

use srag\asq\Test\Domain\Test\Model\TestData;
use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventUser;

/**
 * Class StoreTestDataEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class StoreTestDataEvent extends Event
{
    public function __construct(IEventUser $sender, TestData $data)
    {
        parent::__construct($sender, $object);
    }
}