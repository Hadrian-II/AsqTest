<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections;

use ActiveRecord;
use DateTimeImmutable;
use Fluxlabs\Assessment\Test\Domain\Instance\Model\AssessmentInstanceRun;
use ILIAS\Data\UUID\Uuid;

/**
 * Class RunState
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RunState extends ActiveRecord
{
    /**
     * @var int
     *
     * @con_is_primary true
     * @con_is_unique  true
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     * @con_sequence   true
     */
    protected $id;

    /**
     * @var Uuid
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     36
     * @con_index      true
     * @con_is_notnull true
     */
    protected $aggregate_id;

    /**
     * @var Uuid
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     36
     * @con_index      true
     */
    protected $instance_id;

    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     */
    protected $instancestate_id;

    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     */
    protected $state;

    /**
     * @var DateTimeImmutable
     *
     * @con_has_field  true
     * @con_fieldtype  timestamp
     */
    protected $start_time;

    public function getAggregateId() : Uuid
    {
        return $this->aggregate_id;
    }

    public function getInstanceId() : Uuid
    {
        return $this->instance_id;
    }

    public function getInstanceStateId() : int
    {
        return $this->instancestate_id;
    }

    public function getState() : int
    {
        return $this->state;
    }

    public function getStartTime() : DateTimeImmutable
    {
        return $this->start_time;
    }

    public function setData(Uuid $run_id, Uuid $instance_id, int $instancestate_id, DateTimeImmutable $start_time) : void
    {
        $this->aggregate_id = $run_id;
        $this->instance_id = $instance_id;
        $this->instancestate_id = $instancestate_id;
        $this->start_time = $start_time;
        $this->state = AssessmentInstanceRun::STATE_OPEN;
    }

    public function setState(int $state) : void
    {
        $this->state = $state;
    }
}
