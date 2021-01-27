<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Event;

use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Domain\Model\Hint\QuestionHint;
use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use ILIAS\Data\UUID\Factory;

/**
 * Class HintReceivedEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class HintReceivedEvent extends AbstractDomainEvent
{
    const KEY_QUESTION_ID = 'quid';
    const KEY_HINT = 'hint';

    /**
     * @var Uuid
     */
    protected $question_id;

    /**
     * @var QuestionHint
     */
    protected $hint;

    /**
     * @param Uuid $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param Uuid $question_id
     * @param QuestionHint $hint
     */
    public function __construct(Uuid $aggregate_id, ilDateTime $occured_on, int $initiating_user_id, Uuid $question_id = null, QuestionHint $hint = null)
    {
        $this->question_id = $question_id;
        $this->hint = $hint;
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
     * @return QuestionHint
     */
    public function getHint() : QuestionHint
    {
        return $this->hint;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        $body = [];
        $body[self::KEY_QUESTION_ID] = $this->question_id->toString();
        $body[self::KEY_HINT] = $this->hint;
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
        $this->hint = QuestionHint::createFromArray($body[self::KEY_HINT]);
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
