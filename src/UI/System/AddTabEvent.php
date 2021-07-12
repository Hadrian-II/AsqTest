<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI\System;

use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventUser;

/**
 * Class AddTabEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG <adi@fluxlabs.ch>
 */
class AddTabEvent extends Event {
    public function __construct(IEventUser $sender, TabDefinition $tab)
    {
        parent::__construct($sender, $tab);
    }
}