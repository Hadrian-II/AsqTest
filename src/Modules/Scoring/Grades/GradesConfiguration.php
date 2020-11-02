<?php

namespace srag\asq\Test\Modules\Scoring\Grades;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class GradesConfiguration
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class GradesConfiguration extends AbstractValueObject
{
    /**
     * @var ?GradeDefinition[]
     */
    protected $grades;

    /**
     * @param ?GradeDefinition[] $grades
     * @return GradesConfiguration
     */
    public static function create(?array $grades) : GradesConfiguration
    {
        $object = new GradesConfiguration();
        $object->grades = $grades;
        return $object;
    }

    /**
     * @return ?GradeDefinition[]
     */
    public function getGrades() : ?array
    {
        return $this->grades;
    }
}