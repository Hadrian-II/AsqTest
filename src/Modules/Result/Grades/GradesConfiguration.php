<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Result\Grades;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class GradesConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class GradesConfiguration extends AbstractValueObject
{
    /**
     * @var ?GradeDefinition[]
     */
    protected ?array $grades;

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