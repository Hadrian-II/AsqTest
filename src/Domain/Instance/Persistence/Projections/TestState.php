<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections;

use ActiveRecord;
use ILIAS\Data\UUID\Factory;
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
    const STORAGE_NAME = 'asqt_test_state';

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
    protected $current_instance_id;
    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     */
    protected $current_instance_state_id;

    public function getId() : int
    {
        return intval($this->id);
    }

    public function getAggregateId() : Uuid
    {
        return $this->aggregate_id;
    }

    public function setAggregateId(Uuid $id) : void
    {
        $this->aggregate_id = $id;
    }

    public function getCurrentInstanceId() : ?Uuid
    {
        return $this->current_instance_id;
    }

    public function getCurrentInstanceStateId() : int
    {
        return intval($this->current_instance_state_id);
    }

    public function setCurrentInstance(InstanceState $state) : void
    {
        $this->current_instance_id = $state->getAggregateId();
        $this->current_instance_state_id = $state->getId();
    }

    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'aggregate_id':
                return $this->aggregate_id ? $this->aggregate_id->toString() : null;
            case 'current_instance_id':
                return $this->current_instance_id ? $this->current_instance_id->toString() : null;
            default:
                return null;
        }
    }

    public function wakeUp($field_name, $field_value)
    {
        $factory = new Factory();

        switch ($field_name) {
            case 'aggregate_id':
            case 'current_instance_id':
                return $field_value ? $factory->fromString($field_value) : null;
            default:
                return null;
        }
    }
}
