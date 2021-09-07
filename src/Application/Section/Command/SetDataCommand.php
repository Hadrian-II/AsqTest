<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Section\Command;

use ILIAS\Data\UUID\Uuid;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionData;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class SetDataCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SetDataCommand extends AbstractCommand
{
    public Uuid $section_id;

    public AssessmentSectionData $data;

    public function __construct(Uuid $section_id, int $user_id, AssessmentSectionData $data)
    {
        $this->section_id = $section_id;
        $this->data = $data;
        parent::__construct($user_id);
    }

    public function getSectionId() : Uuid
    {
        return $this->section_id;
    }

    public function getData() : AssessmentSectionData
    {
        return $this->data;
    }
}
