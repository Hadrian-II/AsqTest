<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI\System;

use srag\asq\Test\Lib\Event\Event;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class UIData
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG <adi@fluxlabs.ch>
 */
class UIData extends AbstractValueObject {
    private ?string $title;

    private ?string $description;

    private ?array $tabs;

    private ?array $alerts;

    private ?string $content;

    public function __construct(
        string $title = null,
        string $description = null,
        array $tabs = null,
        array $alerts = null,
        string $content = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->tabs = $tabs;
        $this->alerts = $alerts;
        $this->content = $content;
    }

    public function getTabs(): ?array
    {
        return $this->tabs;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAlerts(): ?array
    {
        return $this->alerts;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}