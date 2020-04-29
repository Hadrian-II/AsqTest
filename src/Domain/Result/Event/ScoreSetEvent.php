<?php

namespace srag\asq\Test\Domain\Result\Event;

use ilDateTime;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\AbstractDomainEvent;
use srag\asq\Test\Domain\Result\Model\ItemScore;

/**
 * Class ScoreSetEvent
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class ScoreSetEvent extends AbstractDomainEvent {
    const KEY_QUESTION_ID = 'quid';
    const KEY_SCORE = 'score';
    
    /**
     * @var string
     */
    protected $question_id;
    
    /**
     * @var ItemScore
     */
    protected $score;
    
    /**
     * @param DomainObjectId $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param string $question_id
     * @param ItemScore $score
     */
    public function __construct(DomainObjectId $aggregate_id, ilDateTime $occured_on, int $initiating_user_id, string $question_id = null, ItemScore $score = null) {
        $this->question_id = $question_id;
        $this->score = $score;
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
     * @return ItemScore
     */
    public function getScore()
    {
        return $this->score;
    }
    
    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody(): string
    {
        $body = [];
        $body[self::KEY_QUESTION_ID] = $this->question_id;
        $body[self::KEY_SCORE] = $this->score;
        return json_encode($body);
    }
    
    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body): void
    {
        $body = json_decode($event_body, true);
        $this->question_id = $body[self::KEY_QUESTION_ID];
        $this->answer = ItemScore::createFromArray($body[self::KEY_SCORE]);
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