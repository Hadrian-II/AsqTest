<?php

namespace srag\asq\Test\Modules\UI\TextualInOut;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class TextualInOutConfiguration
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TextualInOutConfiguration extends AbstractValueObject
{
    /**
     * @var ?string
     */
    protected $intro_text;

    /**
     * @var ?string
     */
    protected $outro_text;

    /**
     * @param string $intro_text
     * @param string $outro_text
     * @return TextualInOutConfiguration
     */
    public static function create(
        ?string $intro_text,
        ?string $outro_text
        ) : TextualInOutConfiguration
    {
        $object = new TextualInOutConfiguration();
        $object->intro_text = $intro_text;
        $object->outro_text = $outro_text;
        return $object;
    }
    /**
     * @return ?string
     */
    public function getIntroText() : ?string
    {
        return $this->intro_text;
    }

    /**
     * @return ?string
     */
    public function getOutroText() : ?string
    {
        return $this->outro_text;
    }
}