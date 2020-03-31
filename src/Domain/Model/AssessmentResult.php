<?php

namespace srag\asq\Test\DomainModel;

use ilDateTime;
use srag\CQRS\Aggregate\AbstractEventSourcedAggregateRoot;
use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\Model\Answer\Answer;
use srag\asq\Test\Events\AssessmentResultAnswerSetEvent;
use srag\asq\Test\Events\AssessmentResultInitiatedEvent;

/**
 * Class AssessmentResult
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentResult extends AbstractEventSourcedAggregateRoot{
    /**
     * @var AssessmentContext
     */
    protected $context;
    
    /**
     * @var Answer[]
     */
    protected $questions;
    
    public static function create(DomainObjectId $id, AssessmentContext $context, array $question_ids, int $user_id) : AssessmentResult {
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
       
    protected function applyAssessmentResultInitiatedEvent(AssessmentResultInitiatedEvent $event) {
        $this->context = $event->getContext();
        $this->questions = [];
        
        foreach ($event->getQuestions() as $question_id) {
            $this->questions[$question_id] = null;
        }
    }
    
    protected function applyAssessmentResultAnswerSetEvent(AssessmentResultAnswerSetEvent $event) {
        $this->questions[$event->getQuestionId()] = $event->getAnswer();
    }
    
    public function getContext() : AssessmentContext {
        return $this->context;
    }
    
    public function getAnswer(string $question_id) : ?Answer {
        if (array_key_exists($question_id, $this->questions)) {
            return $this->questions[$question_id];
        }
        else 
        {
            throw new AsqException('Question is not part of current Assesment');
        }
    }
    
    public function setAnswer(string $question_id, Answer $answer, int $initiating_user_id) {
        if (array_key_exists($question_id, $this->questions)) {
            $this->ExecuteEvent(new AssessmentResultAnswerSetEvent(
                $this->getAggregateId(), new ilDateTime(time(), IL_CAL_UNIX), $initiating_user_id, $question_id, $answer));
        }
        else
        {
            throw new AsqException('Question is not part of current Assesment');
        }
    }
    
    public function getQuestions() : array {
        return array_keys($this->questions);
    }
}