<?php

namespace srag\asq\Test\Command;

use srag\CQRS\Command\AbstractCommand;
use srag\asq\Domain\Model\Answer\Answer;

/**
 * Class AddAnswerCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddAnswerCommand extends AbstractCommand {
    /**
     * @var string
     */
    public $result_uuid;
    
    /**
     * @var string
     */
    public $question_id;
    
    /**
     * @var Answer
     */
    public $answer;
    
    /**
     * @param string $assessment_name
     * @param string $question_id
     * @param Answer $answer
     */
    public function __construct(string $result_uuid, int $user_id, string $question_id, Answer $answer) {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->answer = $answer;
        parent::__construct($user_id);
    }
    
    /**
     * @return string
     */
    public function getResultUuid() : string
    {
        return $this->result_uuid;
    }
    
    /**
     * @return string
     */
    public function getQuestionId() : string
    {
        return $this->question_id;
    }
    
    /**
     * @return Answer
     */
    public function getAnswer() : Answer
    {
        return $this->answer;
    }
}