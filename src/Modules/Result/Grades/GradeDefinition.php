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
     */
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