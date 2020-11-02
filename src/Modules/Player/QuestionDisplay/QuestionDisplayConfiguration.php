<?php

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
    const SHOW_HEADER_WITH_POINTS = 'header_with_points';
    const SHOW_HEADER = 'header';
    const SHOW_NOTHING = 'nothing';

    /**
     * @var ?string
     */
    protected $header_display_mode;

    /**
     * @param string $header
     * @return QuestionDisplayConfiguration
     */
    public static function create(?string $header_display_mode) : QuestionDisplayConfiguration
    {
        $object = new QuestionDisplayConfiguration();
        $object->header_display_mode = $header_display_mode;
        return $object;
    }

    /**
     * @return ?string
     */
    public function getHeaderDisplayMode() : ?string
    {
        return $this->header_display_mode;
    }
}