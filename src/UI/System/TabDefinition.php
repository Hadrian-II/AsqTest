<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI\System;

/**
 * Class TabDefinition
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG <adi@fluxlabs.ch>
 */
class TabDefinition
{
    private string $id;

    private string $text;

    private string $link;

    private bool $active;

    public function __construct(string $id, string $text, string $link, bool $active = false)
    {
        $this->id = $id;
        $this->text = $text;
        $this->link = $link;
        $this->active = $active;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getText() : string
    {
        return $this->text;
    }

    public function getLink() : string
    {
        return $this->link;
    }

    public function isActive() : bool
    {
        return $this->active;
    }
}