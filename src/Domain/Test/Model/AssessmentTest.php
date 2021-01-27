<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Model;

use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\asq\Test\Domain\Test\Event\TestDataSetEvent;
use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Test\Domain\Test\Event\TestConfigurationSetEvent;

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
     * @var AbstractValueObject[]
     */
    protected $configurations = [];

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

    /**
     * @param AbstractValueObject $configuration
     * @param string $configuration_for
     * @param int $user_id
     */
    public function setConfiguration(
        AbstractValueObject $configuration,
        string $configuration_for,
        int $user_id) : void
    {
        if (! array_key_exists($configuration_for, $this->configurations) ||
            ! AbstractValueObject::isNullableEqual($configuration, $this->configurations[$configuration_for])) {
            $this->ExecuteEvent(
                new TestConfigurationSetEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $configuration,
                    $configuration_for)
                );
        }
    }

    /**
     * @param string $configuration_for
     * @return AbstractValueObject|NULL
     */
    public function getConfiguration(string $configuration_for) : ?AbstractValueObject
    {
        return $this->configurations[$configuration_for];
    }

    /**
     * @param TestConfigurationSetEvent $event
     */
    protected function applyTestConfigurationSetEvent(TestConfigurationSetEvent $event) : void
    {
        $this->configurations[$event->getConfigFor()] = $event->getConfig();
    }
}