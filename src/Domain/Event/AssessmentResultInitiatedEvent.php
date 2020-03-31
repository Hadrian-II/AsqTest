<?php

namespace srag\asq\Test\Events;

use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Test\DomainModel\AssessmentContext;

/**
 * Class AssessmentResultInitiatedEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResultInitiatedEvent extends AbstractDomainEvent {
    const KEY_CONTEXT = 'context';
    const KEY_QUESTIONS = 'questions';
    
    /**
     * @var AssessmentContext
     */
    protected $context;

    /**
     * @var string[]
     */
    protected $questions;

    /**
     * @param DomainObjectId $aggregate_id
     * @param int $initiating_user_id
     * @param AssessmentContext $context
     * @param array $questions
     */
    public function __construct(DomainObjectId $aggregate_id, 
                                ilDateTime $occured_on, 
                                int $initiating_user_id, 
                                AssessmentContext $context = null, 
                                array $questions = null)
    {
        $this->context = $context;
        $this->questions = $questions;
        parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return AssessmentContext
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

    protected function restoreEventBody(string $event_body): void
    {
        $body = json_decode($event_body, true);
        $this->questions = $body[self::KEY_QUESTIONS];
        $this->context = AbstractValueObject::createFromArray($body[self::KEY_CONTEXT]);
    }
}