<?php
declare(strict_types = 1);

namespace srag\asq\Test\Lib\Event;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class Event
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class Event
{
    private IEventUser $sender;

    private $data;

    public function __construct(IEventUser $sender, $data) {
        $this->sender = $sender;
        $this->data = $data;
    }

    public function getSender() : IEventUser
    {
        return $this->sender;
    }

    public function getData()
    {
        return $this->data;
    }
}