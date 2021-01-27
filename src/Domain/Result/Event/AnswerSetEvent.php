<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Event\AbstractDomainEvent;
use ILIAS\Data\UUID\Factory;

/**
 * Class AnswerSetEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AnswerSetEvent extends AbstractDomainEvent
{
    const KEY_QUESTION_ID = 'quid';
    const KEY_ANSWER = 'answer';

    /**
     * @var Uuid
     */
    protected $question_id;

    /**
     * @var AbstractValueObject
     */
    protected $answer;

    /**
     * @param Uuid $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param string $question_id
     * @param AbstractValueObject $answer
     */
    public function __construct(Uuid $aggregate_id, ilDateTime $occured_on, int $initiating_user_id, Uuid $question_id = null, AbstractValueObject $answer = null)
    {
        $this->question_id = $question_id;
        $this->answer = $answer;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return Uuid
     */
    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    /**
     * @return AbstractValueObject
     */
    public function getAnswer() : AbstractValueObject
    {
        return $this->answer;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        $body = [];
        $body[self::KEY_QUESTION_ID] = $this->question_id->toString();
        $body[self::KEY_ANSWER] = $this->answer;
        return json_encode($body);
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();

        $body = json_decode($event_body, true);
        $this->question_id = $factory->fromString($body[self::KEY_QUESTION_ID]);
        $this->answer = AbstractValueObject::createFromArray($body[self::KEY_ANSWER]);
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
