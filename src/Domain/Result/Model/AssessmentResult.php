<?php

namespace srag\asq\Test\Domain\Result\Model;

use ilDateTime;
use srag\CQRS\Aggregate\AbstractEventSourcedAggregateRoot;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\Model\Answer\Answer;
use srag\asq\Test\Domain\Result\Event\AnswerSetEvent;
use srag\asq\Test\Domain\Result\Event\AssessmentResultInitiatedEvent;
use srag\asq\Domain\Model\Hint\QuestionHint;
use srag\asq\Test\Domain\Result\Event\HintReceivedEvent;

/**
 * Class AssessmentResult
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResult extends AbstractEventSourcedAggregateRoot{
    /**
     * @var AssessmentResultContext
     */
    protected $context;
    
    /**
     * @var ItemResult[]
     */
    protected $results;
    
    /**
     * @param DomainObjectId $id
     * @param AssessmentResultContext $context
     * @param array $question_ids
     * @param int $user_id
     * @return AssessmentResult
     */
    public static function create(DomainObjectId $id, AssessmentResultContext $context, array $question_ids, int $user_id) : AssessmentResult {
        $result = new AssessmentResult();
        $occured_on = new ilDateTime(time(), IL_CAL_UNIX);
        $result->ExecuteEvent(
            new AggregateCreatedEvent(
                $id, 
                $occured_on, 
                $user_id));
        $result->ExecuteEvent(
            new AssessmentResultInitiatedEvent(
                $id,
                $occured_on,
                $user_id, 
                $context, 
                $question_ids));
        
        return $result;
    }
    
    /**
     * @param AssessmentResultInitiatedEvent $event
     */
    protected function applyAssessmentResultInitiatedEvent(AssessmentResultInitiatedEvent $event) {
        $this->context = $event->getContext();
        $this->results = [];
        
        $ix = 1;
        foreach ($event->getQuestions() as $question_id) {
            $this->results[$question_id] = ItemResult::create($question_id, $ix);
            $ix += 1;
        }
    }
    
    /**
     * @param AnswerSetEvent $event
     */
    protected function applyAnswerSetEvent(AnswerSetEvent $event) {
        $result = $this->results[$event->getQuestionId()];
        $this->results[$event->getQuestionId()] = $result->withAnswer($event->getAnswer());
    }
    
    /**
     * @param HintReceivedEvent $event
     */
    protected function applyHintReceivedEvent(HintReceivedEvent $event) {
        $result = $this->results[$event->getQuestionId()];
        $this->results[$event->getQuestionId()] = $result->withAddedHint($event->getHint());
    }
    
    /**
     * @return AssessmentResultContext
     */
    public function getContext() : AssessmentResultContext {
        return $this->context;
    }
    
    /**
     * @param string $question_id
     * @throws AsqException
     * @return Answer|NULL
     */
    public function getAnswer(string $question_id) : ?Answer {
        if (array_key_exists($question_id, $this->questions)) {
            return $this->questions[$question_id];
        }
        else 
        {
            throw new AsqException('Question is not part of current Assesment');
        }
    }
    
    /**
     * @param string $question_id
     * @param Answer $answer
     * @param int $initiating_user_id
     * @throws AsqException
     */
    public function setAnswer(string $question_id, Answer $answer, int $initiating_user_id) {
        if (array_key_exists($question_id, $this->questions)) {
            $this->ExecuteEvent(new AnswerSetEvent(
                $this->getAggregateId(), new ilDateTime(time(), IL_CAL_UNIX), $initiating_user_id, $question_id, $answer));
        }
        else
        {
            throw new AsqException('Question is not part of current Assesment');
        }
    }
    
    /**
     * @param string $question_id
     * @param QuestionHint $hint
     * @param int $initiating_user_id
     * @throws AsqException
     */
    public function addHint(string $question_id, QuestionHint $hint, int $initiating_user_id) {
        if (array_key_exists($question_id, $this->questions)) {
            $this->ExecuteEvent(
                new HintReceivedEvent(
                    $this->getAggregateId(), new ilDateTime(time(), IL_CAL_UNIX), 
                    $initiating_user_id, 
                    $question_id, 
                    $hint));
        }
        else
        {
            throw new AsqException('Question is not part of current Assesment');
        }
    }
    
    /**
     * @return array
     */
    public function getQuestions() : array {
        return array_keys($this->questions);
    }
}