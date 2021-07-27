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
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AnswerSetEvent extends AbstractDomainEvent
{
    const KEY_QUESTION_ID = 'quid';
    const KEY_ANSWER = 'answer';

    protected ?Uuid $question_id;

    protected ?AbstractValueObject $answer;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occurred_on,
        int $initiating_user_id,
        Uuid $question_id = null,
        AbstractValueObject $answer = null
    ) {
        $this->question_id = $question_id;
        $this->answer = $answer;
        parent::__construct($aggregate_id, $occurred_on, $initiating_user_id);
    }

    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    public function getAnswer() : AbstractValueObject
    {
        return $this->answer;
    }

    public function getEventBody() : string
    {
        $body = [];
        $body[self::KEY_QUESTION_ID] = $this->question_id->toString();
        $body[self::KEY_ANSWER] = $this->answer;
        return json_encode($body);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();

        $body = json_decode($event_body, true);
        $this->question_id = $factory->fromString($body[self::KEY_QUESTION_ID]);
        $this->answer = AbstractValueObject::createFromArray($body[self::KEY_ANSWER]);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
