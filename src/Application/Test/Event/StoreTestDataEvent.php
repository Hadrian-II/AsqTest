<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Event;

use Fluxlabs\Assessment\Tools\Event\Event;
use Fluxlabs\Assessment\Tools\Event\IEventUser;
use Fluxlabs\Assessment\Test\Domain\Test\Model\TestData;
/**
 * Class StoreTestDataEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class StoreTestDataEvent extends Event
{
    private TestData $data;

    public function __construct(IEventUser $sender, TestData $data)
    {
        $this->data = $data;

        parent::__construct($sender);
    }

    public function getData() : TestData
    {
        return $this->data;
    }
}