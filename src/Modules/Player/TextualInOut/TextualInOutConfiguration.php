<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\TextualInOut;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class TextualInOutConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
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
     */
    public function __construct(
        ?string $intro_text = null,
        ?string $outro_text = null
    ) {
        $this->intro_text = $intro_text;
        $this->outro_text = $outro_text;
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