<?php

namespace srag\asq\Test\Modules\Scoring\Grades;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class GradeDefinition
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class GradeDefinition extends AbstractValueObject
{
    /**
     * @var ?string
     */
    protected $short_text;

    /**
     * @var ?string
     */
    protected $official_text;

    /**
     * @var ?float
     */
    protected $percentage;

    /**
     * @var ?bool
     */
    protected $passing;

    /**
     * @param string $short_text
     * @param string $official_text
     * @param float $percentage
     * @param bool $passing
     * @return GradeDefinition
     */
    public static function create(
        ?string $short_text,
        ?string $official_text,
        ?float $percentage,
        ?bool $passing) : GradeDefinition
    {
        $object = new GradeDefinition();
        $object->short_text = $short_text;
        $object->official_text = $official_text;
        $object->percentage = $percentage;
        $object->passing = $passing;
        return $object;
    }
    /**
     * @return ?string
     */
    public function getShortText() : ?string
    {
        return $this->short_text;
    }

    /**
     * @return ?string
     */
    public function getOfficialText() : ?string
    {
        return $this->official_text;
    }

    /**
     * @return ?float
     */
    public function getPercentage() : ?float
    {
        return $this->percentage;
    }

    /**
     * @return ?bool
     */
    public function getPassing() : ?bool
    {
        return $this->passing;
    }
}