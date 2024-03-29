<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Test\Model;

use Fluxlabs\Assessment\Tools\Domain\Model\PluginAggregateRoot;
use Fluxlabs\CQRS\Event\Standard\AggregateCreatedEvent;
use Fluxlabs\Assessment\Test\Domain\Test\Event\TestDataSetEvent;
use ILIAS\Data\UUID\Uuid;
use DateTimeImmutable;
use srag\asq\Application\Exception\AsqException;
use Fluxlabs\Assessment\Test\Domain\Test\Event\TestSectionAddedEvent;
use Fluxlabs\Assessment\Test\Domain\Test\Event\TestSectionRemovedEvent;

/**
 * Class AssessmentTest
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTest extends PluginAggregateRoot
{
    protected ?TestData $data = null;

    protected array $sections = [];

    public static function createNewTest(
        Uuid $uuid
     ) : AssessmentTest {
            $test = new AssessmentTest();
            $test->ExecuteEvent(
                new AggregateCreatedEvent(
                    $uuid,
                    new DateTimeImmutable(),
                )
            );

            return $test;
    }

    public function setTestData(?TestData $data) : void
    {
        if (! TestData::isNullableEqual($data, $this->data)) {
            $this->ExecuteEvent(
                new TestDataSetEvent(
                    $this->aggregate_id,
                    new DateTimeImmutable(),
                    $data
                )
            );
        }
    }

    public function getTestData() : ?TestData
    {
        return $this->data;
    }

    protected function applyTestDataSetEvent(TestDataSetEvent $event) : void
    {
        $this->data = $event->getTestData();
    }

    public function addSection(Uuid $section_id) : void
    {
        if (!in_array($section_id, $this->sections)) {
            $this->ExecuteEvent(
                new TestSectionAddedEvent(
                    $this->aggregate_id,
                    new DateTimeImmutable(),
                    $section_id
                )
            );
        }
        else {
            throw new AsqException('Section is already part of Test');
        }
    }

    protected function applyTestSectionAddedEvent(TestSectionAddedEvent $event) : void
    {
        $this->sections[] = $event->getSectionId();
    }

    public function removeSection(Uuid $section_id) : void
    {
        if (in_array($section_id, $this->sections)) {
            $this->ExecuteEvent(
                new TestSectionRemovedEvent(
                    $this->aggregate_id,
                    new DateTimeImmutable(),
                    $section_id
                )
            );
        }
        else {
            throw new AsqException('Section is not part of Test');
        }
    }

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