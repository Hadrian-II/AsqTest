<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Model;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Aggregate\AbstractValueObject;


/**
 * Class AssessmentTest
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentTestDto
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var ?TestData
     */
    private $data;

    /**
     * @var AbstractValueObject[]
     */
    private $configurations;

    /**
     * @var Uuid[]
     */
    private $sections;

    /**
     * @param AssessmentTest $test
     */
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

    /**
     * @param string $configuration_for
     * @return AbstractValueObject|NULL
     */
    public function getConfiguration(string $configuration_for) : ?AbstractValueObject
    {
        return $this->configurations[$configuration_for];
    }

    public function setConfiguration(string $configuration_for, AbstractValueObject $config) : void
    {
        $this->configurations[$configuration_for] = $config;
    }

    /**
     * @return ?TestData
     */
    public function getTestData() : ?TestData
    {
        return $this->data;
    }

    /**
     * @param TestData $data
     */
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

    /**
     * @param Uuid $section_id
     */
    public function addSection(Uuid $section_id) : void
    {
        $this->sections[] = $section_id;
    }

    /**
     * @param Uuid $section_id
     */
    public function removeSection(Uuid $section_id) : void
    {
        $this->sections = array_diff($this->sections, [$section_id]);
    }
}