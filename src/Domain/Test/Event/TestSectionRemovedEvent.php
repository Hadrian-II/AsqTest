<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use DateTimeImmutable;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use ILIAS\Data\UUID\Factory;

/**
 * Class TestSectionRemovedEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestSectionRemovedEvent extends AbstractDomainEvent
{
    protected ?Uuid $section_id;

    public function __construct(
        Uuid $aggregate_id,
        DateTimeImmutable $occured_on,
        Uuid $section_id = null
        ) {
            $this->section_id = $section_id;
            parent::__construct($aggregate_id, $occured_on);
    }

    public function getSectionId() : ?Uuid
    {
        return $this->section_id;
    }

    public function getEventBody() : string
    {
        return json_encode($this->section_id->toString());
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();
        $this->section_id = $factory->fromString($event_body);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
