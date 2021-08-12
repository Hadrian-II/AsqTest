<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Event\AbstractDomainEvent;

/**
 * Class TestConfigurationRemovedEvent
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestConfigurationRemovedEvent extends AbstractDomainEvent
{
    protected ?string $config_for;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        string $config_for = null
        ) {
            $this->config_for = $config_for;
            parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    public function getConfigFor() : ?string
    {
        return $this->config_for;
    }

    public function getEventBody() : string
    {
        return $this->config_for;
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $this->config_for = $event_body;
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
