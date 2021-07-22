<?php
declare(strict_types = 1);

namespace srag\asq\Test\Lib\Event\Standard;

use srag\asq\Test\Domain\Section\Model\AssessmentSectionData;
use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventUser;

/**
 * Class AddSectionEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AddSectionEvent extends Event
{
    public function __construct(IEventUser $sender, AssessmentSectionData $data)
    {
        parent::__construct($sender, $data);
    }
}