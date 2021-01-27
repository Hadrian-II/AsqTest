<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Event\AbstractDomainEvent;

/**
 * Class TestConfigurationSetEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TestConfigurationSetEvent extends AbstractDomainEvent
{
    const VAR_CONFIG = 'config';
    const VAR_CONFIG_FOR = 'config_for';

    /**
     * @var AbstractValueObject
     */
    protected $config;

    /**
     * @var string
     */
    protected $config_for;

    /**
     * @param Uuid $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param AbstractValueObject $data
     */
    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        AbstractValueObject $config = null,
        string $config_for = null
        ) {
            $this->config = $config;
            $this->config_for = $config_for;
            parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return AbstractValueObject
     */
    public function getConfig() : AbstractValueObject
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getConfigFor() : string
    {
        return $this->config_for;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        $body = [];
        $body[self::VAR_CONFIG] = $this->config;
        $body[self::VAR_CONFIG_FOR] = $this->config_for;
        return json_encode($body);
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        $body = json_decode($event_body, true);
        $this->config = AbstractValueObject::createFromArray($body[self::VAR_CONFIG]);
        $this->config_for = $body[self::VAR_CONFIG_FOR];
    }

    /**
     * @return int
     */
    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
