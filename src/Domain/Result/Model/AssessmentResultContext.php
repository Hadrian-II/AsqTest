<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Model;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentResultContext
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultContext extends AbstractValueObject
{
    protected ?int $user_id;

    protected string $assessment_name;

    protected int $run;

    protected ?string $assessment_id;

    protected ?string $assessment_revision;

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

    public function getUser_id() : int
    {
        return $this->user_id;
    }

    public function getAssessment_name() : string
    {
        return $this->assessment_name;
    }

    public function getRun() : int
    {
        return $this->run;
    }

    public function getAssessment_id() : ?Uuid
    {
        return $this->assessment_id;
    }

    public function getAssessmentRevision() : ?string
    {
        return $this->assessment_revision;
    }
}
