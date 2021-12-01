<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class QuestionDefinition
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDefinition extends AbstractValueObject
{
    protected ?Uuid $question_id;

    protected ?string $revision_name;


    public static function create(Uuid $question_id, ?string $revision_name = null) : QuestionDefinition
    {
        $object = new QuestionDefinition();
        $object->question_id = $question_id;
        $object->revision_name = $revision_name;
        return $object;
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