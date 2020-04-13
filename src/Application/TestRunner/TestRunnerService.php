<?php

namespace srag\asq\Test\Application\TestRunner;

use srag\CQRS\Aggregate\DomainObjectId;
use srag\CQRS\Aggregate\Guid;
use srag\CQRS\Command\CommandBus;
use srag\CQRS\Command\CommandConfiguration;
use srag\CQRS\Command\Access\OpenAccess;
use srag\asq\Application\Service\ASQService;
use srag\asq\Domain\Model\Answer\Answer;
use srag\asq\Test\Application\TestRunner\Command\AddAnswerCommand;
use srag\asq\Test\Application\TestRunner\Command\AddAnswerCommandHandler;
use srag\asq\Test\Application\TestRunner\Command\StartAssessmentCommand;
use srag\asq\Test\Application\TestRunner\Command\StartAssessmentCommandHandler;
use srag\asq\Test\Application\TestRunner\Command\SubmitAssessmentCommand;
use srag\asq\Test\Application\TestRunner\Command\SubmitAssessmentCommandHandler;
use srag\asq\Test\Domain\Result\Model\AssessmentResultContext;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;


/**
 * Class TestRunnerService
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */

class TestRunnerService extends ASQService {
    /**
     * @var CommandBus
     */
    private $command_bus;
    
    private function getCommandBus() : CommandBus {
        if (is_null($this->command_bus)) {
            $this->command_bus = new CommandBus();
            
            $this->command_bus->registerCommand(new CommandConfiguration(
                AddAnswerCommand::class,
                new AddAnswerCommandHandler(),
                new OpenAccess()
                ));
            
            $this->command_bus->registerCommand(new CommandConfiguration(
                StartAssessmentCommand::class,
                new StartAssessmentCommandHandler(),
                new OpenAccess()
                ));
            
            $this->command_bus->registerCommand(new CommandConfiguration(
                SubmitAssessmentCommand::class,
                new SubmitAssessmentCommandHandler(),
                new OpenAccess()
                ));
        }
        
        return $this->command_bus;
    }
    
    public function createTestRun(AssessmentResultContext $context, array $question_ids) : string {
        $uuid = Guid::create();
        
        // CreateQuestion.png
        $this->getCommandBus()->handle(
            new StartAssessmentCommand(
                $uuid,
                $this->getActiveUser(),
                $context,
                $question_ids));
        
        return $uuid;
    }
    
    public function addAnswer(string $uuid, string $question_id, Answer $answer) {
        $this->getCommandBus()->getCommandBus()->handle(
            new AddAnswerCommand(
                $uuid, 
                $this->getActiveUser(), 
                $question_id, 
                $answer));
    }
    
    public function getAnswer(string $uuid, string $question_id) : ?Answer {
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById(new DomainObjectId($uuid));
        
        return $assessment_result->getAnswer($question_id);
    }
    
    public function getFirstQuestionId(string $uuid) : string {
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById(new DomainObjectId($uuid));
        
        return $assessment_result->getQuestions()[0];
    }
    
    public function getPreviousQuestionId(string $uuid, string $question_id) : ?string{
        $questions = AssessmentResultRepository::getInstance()->getAggregateRootById(new DomainObjectId($uuid))->getQuestions();
        
        $current_id = array_search($question_id, $questions);
        
        if ($current_id > 0) {
            return $questions[$current_id - 1];
        }
        else {
            return null;
        }
    }
    
    public function getNextQuestionId(string $uuid, string $question_id) : ?string {
        $questions = AssessmentResultRepository::getInstance()->getAggregateRootById(new DomainObjectId($uuid))->getQuestions();
        
        $current_id = array_search($question_id, $questions);
        
        if (array_key_exists($current_id + 1, $questions)) {
            return $questions[$current_id + 1];
        }
        else {
            return null;
        }
    }
    
    public function submitTestRun(string $name) {
        
    }
    
    public function getTestResult(string $name) {
        
    }
}