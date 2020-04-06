<?php

namespace srag\asq\Test\Domain\Section\Model;

use srag\CQRS\Aggregate\AbstractEventSourcedAggregateRoot;
use srag\asq\Test\Domain\Section\Event\AssessmentSectionDataSetEvent;
use ilDateTime;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\asq\Test\Domain\Section\Event\AssessmentSectionItemAddedEvent;
use srag\asq\Test\Domain\Section\Event\AssessmentSectionItemRemovedEvent;

/**
 * Class AssessmentSection
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentSection extends AbstractEventSourcedAggregateRoot {    
    /**
     * @var ?AssessmentSectionData
     */
    protected $data;
    
    /**
     * @var ?SectionPart[]
     */
    protected $items = [];
    
    /**
     * @param DomainObjectId $id
     * @param int $user_id
     * @return AssessmentSection
     */
    public static function create(DomainObjectId $id, int $user_id) : AssessmentSection {
        $object = new AssessmentSection();
        
        $object->ExecuteEvent(
            new AggregateCreatedEvent(
                $id, 
                new ilDateTime(time(), IL_CAL_UNIX),
                $user_id));
        
        return $object;
    }
    
    /**
     * @return ?AssessmentSectionData
     */
    public function getData(): ?AssessmentSectionData {
        return $this->data;
    }
    
    /**
     * @param ?AssessmentSectionData $data
     * @param int $user_id
     */
    public function setData(?AssessmentSectionData $data, int $user_id) {
        if (! AssessmentSectionData::isNullableEqual($data, $this->data)) {
            $this->ExecuteEvent(
                new AssessmentSectionDataSetEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $data));
        }
    }
    
    /**
     * @param SectionPart $item
     * @param int $user_id
     */
    public function addItem(SectionPart $item, int $user_id) {
        if (!array_key_exists($item->getKey(), $this->items)) {
            $this->ExecuteEvent(new AssessmentSectionItemAddedEvent(
                $this->aggregate_id, 
                new ilDateTime(time(), IL_CAL_UNIX),
                $user_id, 
                $item));
        } else {
            //TODO throw exception?
        }
    }
    
    /**
     * @param AssessmentSectionItemAddedEvent $event
     */
    protected function applyAssessmentSectionItemAddedEvent(AssessmentSectionItemAddedEvent $event) {
        $this->items[$event->getItem()->getKey()] = $event->getItem();
    }
    
    /**
     * @param SectionPart $item
     * @param int $user_id
     */
    public function removeItem(SectionPart $item, int $user_id) {
        if (array_key_exists($item->getKey(), $this->items)) {
            $this->ExecuteEvent(new AssessmentSectionItemRemovedEvent(
                $this->aggregate_id,
                new ilDateTime(time(), IL_CAL_UNIX),
                $user_id,
                $item));
        } else {
            //TODO throw exception?
        }
    }
    
    /**
     * @param AssessmentSectionItemRemovedEvent $event
     */
    protected function applyAssessmentSectionItemRemovedEvent(AssessmentSectionItemRemovedEvent $event) {
        unset($this->items[$event->getItem()->getKey()]);
    }
    
    /**
     * @return array
     */
    public function getItems(): ?array {
        return $this->items;
    }
}