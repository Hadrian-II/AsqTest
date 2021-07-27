<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Result\Grades;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class GradeDefinition
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class GradeDefinition extends AbstractValueObject
{
    protected ?string $short_text;

    protected ?string $official_text;

    protected ?float $percentage;

    protected ?bool $passing;

    public function __construct(
        ?string $short_text,
        ?string $official_text,
        ?float $percentage,
        ?bool $passing
    ) {
        $this->short_text = $short_text;
        $this->official_text = $official_text;
        $this->percentage = $percentage;
        $this->passing = $passing;
    }

    public function getShortText() : ?string
    {
        return $this->short_text;
    }

    public function getOfficialText() : ?string
    {
        return $this->official_text;
    }

    public function getPercentage() : ?float
    {
        return $this->percentage;
    }

    public function getPassing() : ?bool
    {
        return $this->passing;
    }
}