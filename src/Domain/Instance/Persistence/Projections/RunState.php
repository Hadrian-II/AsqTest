<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections;

use ActiveRecord;
use DateTimeImmutable;
use Fluxlabs\Assessment\Test\Domain\Instance\Model\AssessmentInstanceRun;
use ILIAS\Data\UUID\Factory;
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
    const STORAGE_NAME = 'asqt_run_state';

    public static function returnDbTableName() : string
    {
        return self::STORAGE_NAME;
    }

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
    protected $user_id;

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

    public function getId() : int
    {
        return intval($this->id);
    }

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
        return intval($this->instancestate_id);
    }

    public function getState() : int
    {
        return $this->state;
    }

    public function getStartTime() : DateTimeImmutable
    {
        return $this->start_time;
    }

    public function getUserId() : int
    {
        return $this->user_id;
    }

    public function setData(Uuid $run_id, InstanceState $instance_state, DateTimeImmutable $start_time, int $user_id) : void
    {
        $this->aggregate_id = $run_id;
        $this->instance_id = $instance_state->getId();
        $this->instancestate_id = $instance_state->getAggregateId();
        $this->start_time = $start_time;
        $this->user_id = $user_id;
        $this->state = AssessmentInstanceRun::STATE_OPEN;
    }

    public function setState(int $state) : void
    {
        $this->state = $state;
    }

    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'aggregate_id':
                return $this->aggregate_id ? $this->aggregate_id->toString() : null;
            case 'instance_id':
                return $this->instance_id ? $this->instance_id->toString() : null;
            default:
                return null;
        }
    }

    public function wakeUp($field_name, $field_value)
    {
        $factory = new Factory();

        switch ($field_name) {
            case 'aggregate_id':
            case 'instance_id':
                return $field_value ? $factory->fromString($field_value) : null;
            default:
                return null;
        }
    }
}
