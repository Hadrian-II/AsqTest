<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI\System;

/**
 * Class UIData
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG <adi@fluxlabs.ch>
 */
class UIData
{
    private ?string $title;

    private ?string $description;

    private ?string $content;

    private ?array $toolbar;

    public function __construct(
        ?string $title = null,
        ?string $content = null,
        ?string $description = null,
        ?array $toolbar = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->toolbar = $toolbar;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getToolbar() : ?array
    {
        return $this->toolbar;
    }
}