<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event;

use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionData;

/**
 * Class StoreSectionEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SectionDefinition
{
    protected AssessmentSectionData $data;

    /**
     * @var QuestionDefinition[]
     */
    protected array $questions;

    public function __construct(AssessmentSectionData $data, array $questions)
    {
        $this->data = $data;
        $this->questions = $questions;
    }

    public function getData() : AssessmentSectionData
    {
        return $this->data;
    }

    /**
     * @return QuestionDefinition[]
     */
    public function getQuestions() : array
    {
        return $this->questions;
    }
}