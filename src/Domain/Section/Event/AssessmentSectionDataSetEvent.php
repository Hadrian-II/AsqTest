<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Event;

use ilDateTime;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionData;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentSectionDataSetEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionDataSetEvent extends AbstractDomainEvent
{
    protected ?AssessmentSectionData $section_data;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        AssessmentSectionData $data = null
    ) {
        $this->section_data = $data;
        parent::__construct($aggregate_id, $occured_on);
    }

    public function getSectionData() : AssessmentSectionData
    {
        return $this->section_data;
    }

    public function getEventBody() : string
    {
        return json_encode($this->section_data);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $this->section_data = AssessmentSectionData::deserialize($event_body);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
