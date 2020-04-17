<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use srag\CQRS\Command\AbstractCommand;
use srag\asq\Domain\Model\Hint\QuestionHint;

/**
 * Class HintReceivedCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class HintReceivedCommand extends AbstractCommand {
    /**
     * @var string
     */
    public $result_uuid;
    
    /**
     * @var string
     */
    public $question_id;
    
    /**
     * @var QuestionHint
     */
    public $hint;
    
    /**
     * @param string $assessment_name
     * @param string $question_id
     * @param QuestionHint $answer
     */
    public function __construct(string $result_uuid, int $user_id, string $question_id, QuestionHint $hint) {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->hint = $hint;
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
     * @return QuestionHint
     */
    public function getHint() : QuestionHint
    {
        return $this->hint;
    }
}