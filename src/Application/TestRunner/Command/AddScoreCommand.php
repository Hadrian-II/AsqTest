<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use srag\CQRS\Command\AbstractCommand;
use srag\asq\Test\Domain\Result\Model\ItemScore;

/**
 * Class AddScoreCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddScoreCommand extends AbstractCommand {
    /**
     * @var string
     */
    public $result_uuid;
    
    /**
     * @var string
     */
    public $question_id;
    
    /**
     * @var ItemScore
     */
    public $score;
    
    /**
     * @param string $result_uuid
     * @param int $user_id
     * @param string $question_id
     * @param ItemScore $score
     */
    public function __construct(string $result_uuid, int $user_id, string $question_id, ItemScore $score) {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->score = $score;
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
     * @return ItemScore
     */
    public function getScore() : ItemScore
    {
        return $this->score;
    }
}