<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event;

use ILIAS\Data\UUID\Uuid;

/**
 * Class StoreSectionEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SectionDefinition
{
    protected string $name;

    /**
     * @var Uuid[]
     */
    protected array $questions;

    public function __construct(string $name, array $questions)
    {
        $this->name = $name;
        $this->questions = $questions;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return Uuid[]
     */
    public function getQuestions() : array
    {
        return $this->questions;
    }
}