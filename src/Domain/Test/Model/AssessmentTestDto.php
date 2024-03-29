<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Test\Model;

use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;


/**
 * Class AssessmentTest
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestDto
{
    private Uuid $id;

    private ?TestData $data;

    /**
     * @var AbstractValueObject[]
     */
    private array $configurations;

    /**
     * @var Uuid[]
     */
    private array $sections;

    public function __construct(AssessmentTest $test)
    {
        $this->id = $test->getAggregateId();
        $this->data = $test->getTestData();
        $this->configurations = $test->getConfigurations();
        $this->sections = $test->getSections();
    }

    public function getId() : Uuid {
        return $this->id;
    }

    /**
     * @return AbstractValueObject[]
     */
    public function getConfigurations() : array
    {
        return $this->configurations;
    }

    public function getConfiguration(string $configuration_for) : ?AbstractValueObject
    {
        return $this->configurations[$configuration_for];
    }

    public function setConfiguration(string $configuration_for, AbstractValueObject $config) : void
    {
        $this->configurations[$configuration_for] = $config;
    }

    public function removeConfiguration(string $configuration_for) : void
    {
        unset($this->configurations[$configuration_for]);
    }

    public function getTestData() : ?TestData
    {
        return $this->data;
    }

    public function setTestData(TestData $data) : void
    {
        $this->data = $data;
    }

    /**
     * @return Uuid[]
     */
    public function getSections() : array
    {
        return $this->sections;
    }

    public function addSection(Uuid $section_id) : void
    {
        $this->sections[] = $section_id;
    }

    public function removeSection(Uuid $section_id) : void
    {
        $this->sections = array_diff($this->sections, [$section_id]);
    }
}