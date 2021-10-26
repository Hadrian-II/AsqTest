<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections;

use ActiveRecord;
use DateTimeImmutable;
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
     * @con_fieldtype  timestamp
     */
    protected $instance_opens;

    /**
     * @var DateTimeImmutable
     *
     * @con_has_field  true
     * @con_fieldtype  timestamp
     */
    protected $instance_closes;

    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     */
    protected $allowed_tries;

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

    public function getAllowedTries() : int
    {
        return $this->allowed_tries;
    }

    public function setData(Uuid $instance_id, Uuid $test_id, int $teststate_id, DateTimeImmutable $opening, DateTimeImmutable $closing, int $tries) : void
    {
        $this->aggregate_id = $instance_id;
        $this->test_id = $test_id;
        $this->teststate_id = $teststate_id;
        $this->instance_opens = $opening;
        $this->instance_closes = $closing;
        $this->allowed_tries = $tries;
    }
}
