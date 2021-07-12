<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI\System;

use srag\asq\Test\Lib\Event\Event;
use srag\asq\Test\Lib\Event\IEventUser;

/**
 * Class TestUI
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG <adi@fluxlabs.ch>
 */
class TestUI implements ITestUI, IEventUser
{
    private string $title = '';

    private string $description = '';

    private array $tabs = [];

    private string $content = '';

    private array $toolbar =[];

    function processEvent(Event $event): void
    {
        if (get_class($event) === SetUIEvent::class) {
            /** @var $data UIData */
            $data = $event->getData();

            if ($data->getContent() !== null) {
                $this->content = $data->getContent();
            }

            if ($data->getDescription() !== null) {
                $this->description = $data->getDescription();
            }

            if ($data->getTitle() !== null) {
                $this->title = $data->getTitle();
            }

            if ($data->getToolbar() !== null) {
                foreach ($data->getToolbar() as $tool) {
                    $this->toolbar[] = $tool;
                }
            }
        }

        if (get_class($event) === AddTabEvent::class) {
            $this->tabs[] = $event->getData();
        }
    }

    public function getTabs(): array
    {
        return $this->tabs;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getToolbar(): array
    {
        return $this->toolbar;
    }
}