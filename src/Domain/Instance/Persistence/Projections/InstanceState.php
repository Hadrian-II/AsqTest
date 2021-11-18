<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections;

use ActiveRecord;
use DateTimeImmutable;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;

/**
 * Class InstanceState
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class InstanceState extends ActiveRecord
{
    const STORAGE_NAME = 'asqt_instance_state';

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
    protected $test_id;
    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     */
    protected $teststate_id;

    /**
     * @var DateTimeImmutable
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     */
    protected $instance_opens;

    /**
     * @var DateTimeImmutable
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     */
    protected $instance_closes;

    /**
     * @var float
     *
     * @con_has_field  true
     * @con_fieldtype  float
     */
    protected $max_points;

    public function getId() : int
    {
        return intval($this->id);
    }

    public function getAggregateId() : Uuid
    {
        return $this->aggregate_id;
    }

    public function getTestId() : Uuid
    {
        return $this->test_id;
    }

    public function getTestStateId() : int
    {
        return $this->teststate_id;
    }

    public function getInstanceOpening() : DateTimeImmutable
    {
        return $this->instance_opens;
    }

    public function getInstanceClosing() : DateTimeImmutable
    {
        return $this->instance_closes;
    }

    public function getMaxPoints() : float
    {
        return floatval($this->max_points);
    }

    public function setData(
        Uuid $instance_id,
        Uuid $test_id,
        int $teststate_id,
        DateTimeImmutable $opening,
        DateTimeImmutable $closing,
        float $max_points) : void
    {
        $this->aggregate_id = $instance_id;
        $this->test_id = $test_id;
        $this->teststate_id = $teststate_id;
        $this->instance_opens = $opening;
        $this->instance_closes = $closing;
        $this->max_points = $max_points;
    }

    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'aggregate_id':
                return $this->aggregate_id ? $this->aggregate_id->toString() : null;
            case 'test_id':
                return $this->test_id ? $this->test_id->toString() : null;
            case 'instance_opens':
                return $this->instance_opens ? $this->instance_opens->getTimestamp() : null;
            case 'instance_closes':
                return $this->instance_closes ? $this->instance_closes->getTimestamp() : null;
            default:
                return null;
        }
    }

    public function wakeUp($field_name, $field_value)
    {
        $factory = new Factory();

        switch ($field_name) {
            case 'aggregate_id':
            case 'test_id':
                return $field_value ? $factory->fromString($field_value) : null;
            case 'instance_opens':
            case 'instance_closes':
                return $field_value ? (new DateTimeImmutable())->setTimestamp(intval($field_value)) : null;
            default:
                return null;
        }
    }
}
