<?php

namespace srag\asq\Test\Modules\Result\Grades;

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
     */
    public function __construct(?array $grades)
    {
        $this->grades = $grades;
    }

    /**
     * @return ?GradeDefinition[]
     */
    public function getGrades() : ?array
    {
        return $this->grades;
    }
}