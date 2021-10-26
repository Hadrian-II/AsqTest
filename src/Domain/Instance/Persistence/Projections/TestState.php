<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections;

use ActiveRecord;
use ILIAS\Data\UUID\Uuid;

/**
 * Class TestState
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestState extends ActiveRecord
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
    protected $current_run_id;
    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     */
    protected $current_runstate_id;

    public function getAggregateId() : Uuid
    {
        return $this->aggregate_id;
    }

    public function setAggregateId(Uuid $id) : void
    {
        $this->aggregate_id = $id;
    }

    public function getCurrentRunId() : Uuid
    {
        return $this->current_run_id;
    }

    public function getRunstateId() : int
    {
        return $this->current_runstate_id;
    }

    public function setCurrentRun(Uuid $run_id, int $runstate_id) : void
    {
        $this->current_run_id = $run_id;
        $this->current_runstate_id = $runstate_id;
    }
}
