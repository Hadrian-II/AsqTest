<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Model;

use srag\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentResultContext
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
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

    /**
     * @param int $user_id
     * @param string $assessment_name
     * @param int $run
     * @param Uuid $assessment_id
     * @param string $assessment_revision
     */
    public function __construct(
        int $user_id = null,
        string $assessment_name = null,
        int $run = 1,
        ?Uuid $assessment_id = null,
        ?string $assessment_revision = null
    ) {
        $this->user_id = $user_id;
        $this->assessment_name = $assessment_name;
        $this->run = $run;
        $this->assessment_id = $assessment_id;
        $this->assessment_revision = $assessment_revision;
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
