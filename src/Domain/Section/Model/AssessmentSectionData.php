<?php

namespace srag\asq\Test\Domain\Section\Model;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AssessmentSectionData
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentSectionData extends AbstractValueObject
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var bool
     */
    protected $fixed;

    /**
     * @var bool
     */
    protected $visible;

    /**
     * @var bool
     */
    protected $keep_together;

    /**
     * @param string $title
     * @param bool $visible
     * @param bool $required
     * @param bool $fixed
     * @param bool $keep_together
     */
    public function __construct(
        string $title = '',
        bool $visible = true,
        bool $required = false,
        bool $fixed = false,
        bool $keep_together = true
    ) {
        $this->title = $title;
        $this->visible = $visible;
        $this->required = $required;
        $this->fixed = $fixed;
        $this->keep_together = $keep_together;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isFixed()
    {
        return $this->fixed;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @return bool
     */
    public function isKeep_together()
    {
        return $this->keep_together;
    }
}
