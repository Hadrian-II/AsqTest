<?php

namespace srag\asq\Test\Domain\Test\Model;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class TestDefinition
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TestData extends AbstractValueObject
{
    /**
     * @var ?string
     */
    protected $title;

    /**
     * @var ?string
     */
    protected $description;

    /**
     * @param string $title
     * @param string $description
     */
    public function __construct(?string $title = null, ?string $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return ?string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * @return ?string
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }
}