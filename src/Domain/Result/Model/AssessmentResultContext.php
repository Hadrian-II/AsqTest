<?php

namespace srag\asq\Test\Domain\Result\Model;

use srag\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentResultContext
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResultContext extends AbstractValueObject
{
    /**
     * @var int
     */
    protected $user_id;
    /**
     * @var Uuid
     */
    protected $assessment_name;
    /**
     * @var int
     */
    protected $run;
    /**
     * @var ?string
     */
    protected $assessment_id;
    /**
     * @var ?string
     */
    protected $assessment_revision;

    public static function create(
        int $user_id,
        string $assessment_name,
        int $run = 1,
        ?Uuid $assessment_id = null,
        ?string $assessment_revision = null
    ) : AssessmentResultContext {
        $object = new AssessmentResultContext();
        $object->user_id = $user_id;
        $object->assessment_name = $assessment_name;
        $object->run = $run;
        $object->assessment_id = $assessment_id;
        $object->assessment_revision = $assessment_revision;
        return $object;
    }

    /**
     * @return int
     */
    public function getUser_id() : int
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getAssessment_name() : string
    {
        return $this->assessment_name;
    }

    /**
     * @return int
     */
    public function getRun() : int
    {
        return $this->run;
    }

    /**
     * @return ?Uuid
     */
    public function getAssessment_id() : ?Uuid
    {
        return $this->assessment_id;
    }

    /**
     * @return ?string
     */
    public function getAssessmentRevision()
    {
        return $this->assessment_revision;
    }
}
