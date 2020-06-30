<?php

namespace srag\asq\Test\Domain\Result\Event;

use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Domain\Model\Answer\Answer;

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
     * @var string
     */
    protected $question_id;

    /**
     * @var Answer
     */
    protected $answer;

    /**
     * @param string $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param string $question_id
     * @param Answer $answer
     */
    public function __construct(string $aggregate_id, ilDateTime $occured_on, int $initiating_user_id, string $question_id = null, Answer $answer = null)
    {
        $this->question_id = $question_id;
        $this->answer = $answer;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return string
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * @return Answer
     */
    public function getAnswer()
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
        $body[self::KEY_QUESTION_ID] = $this->question_id;
        $body[self::KEY_ANSWER] = $this->answer;
        return json_encode($body);
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        $body = json_decode($event_body, true);
        $this->question_id = $body[self::KEY_QUESTION_ID];
        $this->answer = Answer::createFromArray($body[self::KEY_ANSWER]);
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
