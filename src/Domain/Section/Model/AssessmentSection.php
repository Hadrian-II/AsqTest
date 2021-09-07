<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Model;

use ilDateTimeException;
use srag\CQRS\Aggregate\AbstractAggregateRoot;
use Fluxlabs\Assessment\Test\Domain\Section\Event\AssessmentSectionDataSetEvent;
use ilDateTime;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use Fluxlabs\Assessment\Test\Domain\Section\Event\AssessmentSectionItemAddedEvent;
use Fluxlabs\Assessment\Test\Domain\Section\Event\AssessmentSectionItemRemovedEvent;
use ILIAS\Data\UUID\Uuid;

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

    public static function create(Uuid $id, int $user_id) : AssessmentSection
    {
        $object = new AssessmentSection();

        $object->ExecuteEvent(
            new AggregateCreatedEvent(
                $id,
                new ilDateTime(time(), IL_CAL_UNIX),
                $user_id
            )
        );

        return $object;
    }

    public function getData() : ?AssessmentSectionData
    {
        return $this->data;
    }

    public function setData(?AssessmentSectionData $data, int $user_id) : void
    {
        if (!AssessmentSectionData::isNullableEqual($data, $this->data)) {
            $this->ExecuteEvent(
                new AssessmentSectionDataSetEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $data
                )
            );
        }
    }

    public function applyAssessmentSectionDataSetEvent(AssessmentSectionDataSetEvent $event) : void
    {
        $this->data = $event->getSectionData();
    }

    public function addItem(SectionPart $item, int $user_id) : void
    {
        if (!array_key_exists($item->getKey(), $this->items)) {
            $this->ExecuteEvent(new AssessmentSectionItemAddedEvent(
                $this->aggregate_id,
                new ilDateTime(time(), IL_CAL_UNIX),
                $user_id,
                $item
            ));
        } else {
            //TODO throw exception?
        }
    }

    protected function applyAssessmentSectionItemAddedEvent(AssessmentSectionItemAddedEvent $event) : void
    {
        $this->items[$event->getItem()->getKey()] = $event->getItem();
    }

    public function removeItem(SectionPart $item, int $user_id) : void
    {
        if (array_key_exists($item->getKey(), $this->items)) {
            $this->ExecuteEvent(new AssessmentSectionItemRemovedEvent(
                $this->aggregate_id,
                new ilDateTime(time(), IL_CAL_UNIX),
                $user_id,
                $item
            ));
        } else {
            //TODO throw exception?
        }
    }

    protected function applyAssessmentSectionItemRemovedEvent(AssessmentSectionItemRemovedEvent $event) : void
    {
        unset($this->items[$event->getItem()->getKey()]);
    }

    public function getItems() : ?array
    {
        return $this->items;
    }
}
