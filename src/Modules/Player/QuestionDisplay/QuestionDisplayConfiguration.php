<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\QuestionDisplay;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionDisplayConfiguration
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionDisplayConfiguration extends AbstractValueObject
{
    const SHOW_HEADER_WITH_POINTS = 1;
    const SHOW_HEADER = 2;
    const SHOW_NOTHING = 3;

    /**
     * @var ?string
     */
    protected $header_display_mode;

    /**
     * @param string $header
     */
    public function __construct(?int $header_display_mode)
    {
        $this->header_display_mode = $header_display_mode;
    }

    /**
     * @return ?string
     */
    public function getHeaderDisplayMode() : ?int
    {
        return $this->header_display_mode;
    }
}