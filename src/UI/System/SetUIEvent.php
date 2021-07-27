<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI\System;

use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventUser;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class SetUIEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG <adi@fluxlabs.ch>
 */
class SetUIEvent extends Event
{
    public function __construct(IEventUser $sender, UIData $data)
    {
        parent::__construct($sender, $data);
    }
}