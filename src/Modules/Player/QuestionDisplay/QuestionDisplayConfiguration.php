<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\QuestionDisplay;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionDisplayConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDisplayConfiguration extends AbstractValueObject
{
    const SHOW_HEADER_WITH_POINTS = 1;
    const SHOW_HEADER = 2;
    const SHOW_NOTHING = 3;

    protected ?int $header_display_mode;

    public function __construct(?int $header_display_mode = null)
    {
        $this->header_display_mode = $header_display_mode;
    }

    public function getHeaderDisplayMode() : ?int
    {
        return $this->header_display_mode;
    }
}