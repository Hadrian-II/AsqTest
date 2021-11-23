<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event;

use ILIAS\Data\UUID\Uuid;

/**
 * Class QuestionDefinition
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDefinition
{
    protected Uuid $question_id;

    protected ?string $revision_name;


    public function __construct(Uuid $question_id, ?string $revision_name = null)
    {
        $this->question_id = $question_id;
        $this->revision_name = $revision_name;
    }

    public function getQuestionId(): Uuid
    {
        return $this->question_id;
    }

    public function getRevisionName(): ?string
    {
        return $this->revision_name;
    }
}