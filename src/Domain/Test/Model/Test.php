<?php

namespace srag\asq\Test\Domain\Test\Model;

use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\asq\Test\Domain\Test\Event\TestDataSetEvent;
use ilDateTime;

/**
 * Class AssessmentTest
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentTest extends AbstractAggregateRoot
{
    /**
     * @var ?TestData
     */
    protected $data;

    /**
     * @param TestData $data
     */
    public function setTestData(?TestData $data, int $user_id) : void
    {
        if (! TestData::isNullableEqual($data, $this->data)) {
            $this->ExecuteEvent(
                new TestDataSetEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $data
                )
            );
        }
    }

    /**
     * @return ?TestData
     */
    public function getTestData() : ?TestData
    {
        return $this->data;
    }

    /**
     * @param TestDataSetEvent $event
     */
    protected function applyTestDataSetEvent(TestDataSetEvent $event) : void
    {
        $this->data = $event->getTestData();
    }
}