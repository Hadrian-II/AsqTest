<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Test\Domain\Test\Model\TestData;

/**
 * Class TestDataSetEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestDataSetEvent extends AbstractDomainEvent
{
    protected ?TestData $test_data;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occurred_on,
        int $initiating_user_id,
        TestData $data = null
        ) {
            $this->test_data = $data;
            parent::__construct($aggregate_id, $occurred_on, $initiating_user_id);
    }

    public function getTestData() : ?TestData
    {
        return $this->test_data;
    }

    public function getEventBody() : string
    {
        return json_encode($this->test_data);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $this->test_data = TestData::deserialize($event_body);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
