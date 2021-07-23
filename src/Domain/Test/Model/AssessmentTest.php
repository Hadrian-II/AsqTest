<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Model;

use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\asq\Test\Domain\Test\Event\TestDataSetEvent;
use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Test\Domain\Test\Event\TestConfigurationSetEvent;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Test\Domain\Test\Event\TestSectionAddedEvent;
use srag\asq\Test\Domain\Test\Event\TestSectionRemovedEvent;

/**
 * Class AssessmentTest
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
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
     * @var Uuid[]
     */
    protected $sections = [];

    /**
     * @param Uuid $uuid
     * @param int $initiating_user_id
     * @return AssessmentTest
     */
    public static function createNewTest(
        Uuid $uuid,
        int $initiating_user_id
     ) : AssessmentTest {
            $test = new AssessmentTest();
            $test->ExecuteEvent(
                new AggregateCreatedEvent(
                    $uuid,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $initiating_user_id
                )
            );

            return $test;
    }

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
     * @return array
     */
    public function getConfigurations() : array
    {
        return $this->configurations;
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

    /**
     * @param Uuid $section_id
     */
    public function addSection(Uuid $section_id, int $user_id) : void
    {
        if (!in_array($section_id, $this->sections)) {
            $this->ExecuteEvent(
                new TestSectionAddedEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $section_id)
                );
        }
        else {
            throw new AsqException('Section is already part of Test');
        }
    }

    /**
     * @param TestSectionAddedEvent $event
     */
    protected function applyTestSectionAddedEvent(TestSectionAddedEvent $event) : void
    {
        $this->sections[] = $event->getSectionId();
    }

    /**
     * @param Uuid $section_id
     */
    public function removeSection(Uuid $section_id, int $user_id) : void
    {
        if (in_array($section_id, $this->sections)) {
            $this->ExecuteEvent(
                new TestSectionRemovedEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $section_id)
                );
        }
        else {
            throw new AsqException('Section is not part of Test');
        }
    }

    /**
     * @param TestSectionRemovedEvent $event
     */
    protected function applyTestSectionRemovedEvent(TestSectionRemovedEvent $event) : void
    {
        $this->sections = array_diff($this->sections, [$event->getSectionId()]);
    }

    /**
     * @return Uuid[]
     */
    public function getSections() : array
    {
        return $this->sections;
    }
}