<?php

namespace srag\asq\Test\Domain\Section\Model;

/**
 * Class AssessmentSectionDto
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentSectionDto
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var AssessmentSectionData
     */
    protected $data;

    /**
     * @var SectionPart[]
     */
    protected $items;

    /**
     * @param AssessmentSection $section
     * @return AssessmentSectionDto
     */
    public static function Create(AssessmentSection $section) : AssessmentSectionDto
    {
        $object = new AssessmentSectionDto();
        $object->id = $section->getAggregateId();
        $object->data = $section->getData();
        $object->items = $section->getItems();
        return $object;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return AssessmentSectionData
     */
    public function getData() : ?AssessmentSectionData
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getItems() : ?array
    {
        return $this->items;
    }
}
