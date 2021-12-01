<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Model;

use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentSectionDto
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionDto
{
    protected Uuid $id;

    protected ?AssessmentSectionData $data;

    /**
     * @var SectionPart[]
     */
    protected ?array $items;

    public static function Create(AssessmentSection $section) : AssessmentSectionDto
    {
        $object = new AssessmentSectionDto();
        $object->id = $section->getAggregateId();
        $object->data = $section->getData();
        $object->items = $section->getItems();
        return $object;
    }

    public function getId() : Uuid
    {
        return $this->id;
    }

    public function getData() : ?AssessmentSectionData
    {
        return $this->data;
    }

    /**
     * @return SectionPart[]|null
     */
    public function getItems() : ?array
    {
        return $this->items;
    }
}
