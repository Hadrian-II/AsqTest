<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use DateTimeImmutable;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use Fluxlabs\Assessment\Test\Domain\Test\Model\TestData;

/**
 * Class TestDataSetEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestDataSetEvent extends AbstractDomainEvent
{
    protected ?TestData $test_data;

    public function __construct(
        Uuid $aggregate_id,
        DateTimeImmutable $occurred_on,
        TestData $data = null
        ) {
            $this->test_data = $data;
            parent::__construct($aggregate_id, $occurred_on);
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
