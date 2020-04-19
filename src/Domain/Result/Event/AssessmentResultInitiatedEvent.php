<?php

namespace srag\asq\Test\Domain\Result\Event;

use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Test\Domain\Result\Model\AssessmentResultContext;

/**
 * Class AssessmentResultInitiatedEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResultInitiatedEvent extends AbstractDomainEvent {
    const KEY_CONTEXT = 'context';
    const KEY_QUESTIONS = 'questions';
    
    /**
     * @var AssessmentResultContext
     */
    protected $context;

    /**
     * @var string[]
     */
    protected $questions;

    /**
     * @param DomainObjectId $aggregate_id
     * @param int $initiating_user_id
     * @param AssessmentResultContext $context
     * @param array $questions
     */
    public function __construct(
        DomainObjectId $aggregate_id, 
        ilDateTime $occured_on, 
        int $initiating_user_id, 
        AssessmentResultContext $context = null, 
        array $questions = null)
    {
        $this->context = $context;
        $this->questions = $questions;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return AssessmentResultContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string[]
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    public function getEventBody(): string
    {
        $body = [];
        $body[self::KEY_CONTEXT] = $this->context;
        $body[self::KEY_QUESTIONS] = $this->questions;
        return json_encode($body);
    }

    /**
     * @param string $event_body
     */
    protected function restoreEventBody(string $event_body): void
    {
        $body = json_decode($event_body, true);
        $this->questions = $body[self::KEY_QUESTIONS];
        $this->context = AbstractValueObject::createFromArray($body[self::KEY_CONTEXT]);
    }
    
    /**
     * @return int
     */
    public static function getEventVersion(): int
    {
        // initial version 1
        return 1;
    }
}