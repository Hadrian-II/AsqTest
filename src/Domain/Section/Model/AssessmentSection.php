<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Model;

use Fluxlabs\CQRS\Aggregate\AbstractAggregateRoot;
use Fluxlabs\Assessment\Test\Domain\Section\Event\AssessmentSectionDataSetEvent;
use DateTimeImmutable;
use Fluxlabs\CQRS\Event\Standard\AggregateCreatedEvent;
use Fluxlabs\Assessment\Test\Domain\Section\Event\AssessmentSectionItemAddedEvent;
use Fluxlabs\Assessment\Test\Domain\Section\Event\AssessmentSectionItemRemovedEvent;
use ILIAS\Data\UUID\Uuid;
use srag\asq\Application\Exception\AsqException;

/**
 * Class AssessmentSection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSection extends AbstractAggregateRoot
{
    protected ?AssessmentSectionData $data = null;

    /**
     * @var ?SectionPart[]
     */
    protected ?array $items = [];

    public static function create(Uuid $id) : AssessmentSection
    {
        $object = new AssessmentSection();

        $object->ExecuteEvent(
            new AggregateCreatedEvent(
                $id,
                new DateTimeImmutable()
            )
        );

        return $object;
    }

    public function getData() : ?AssessmentSectionData
    {
        return $this->data;
    }

    public function setData(?AssessmentSectionData $data) : void
    {
        if (!AssessmentSectionData::isNullableEqual($data, $this->data)) {
            $this->ExecuteEvent(
                new AssessmentSectionDataSetEvent(
                    $this->aggregate_id,
                    new DateTimeImmutable(),
                    $data
                )
            );
        }
    }

    public function applyAssessmentSectionDataSetEvent(AssessmentSectionDataSetEvent $event) : void
    {
        $this->data = $event->getSectionData();
    }

    public function addItem(SectionPart $item) : void
    {
        if (!array_key_exists($item->getId()->toString(), $this->items)) {
            $this->ExecuteEvent(new AssessmentSectionItemAddedEvent(
                $this->aggregate_id,
                new DateTimeImmutable(),
                $item
            ));
        } else {
            throw new AsqException('same item cant be added twice to assessmentsection');
        }
    }

    protected function applyAssessmentSectionItemAddedEvent(AssessmentSectionItemAddedEvent $event) : void
    {
        $this->items[$event->getItem()->getId()->toString()] = $event->getItem();
    }

    public function removeItem(SectionPart $item) : void
    {
        if (array_key_exists($item->getId()->toString(), $this->items)) {
            $this->ExecuteEvent(new AssessmentSectionItemRemovedEvent(
                $this->aggregate_id,
                new DateTimeImmutable(),
                $item
            ));
        } else {
            throw new AsqException('cant remove item that is not part of assessmentsection');
        }
    }

    protected function applyAssessmentSectionItemRemovedEvent(AssessmentSectionItemRemovedEvent $event) : void
    {
        unset($this->items[$event->getItem()->getId()->toString()]);
    }

    public function getItems() : ?array
    {
        return $this->items;
    }
}
