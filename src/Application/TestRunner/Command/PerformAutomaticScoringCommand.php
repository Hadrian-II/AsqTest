<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use srag\CQRS\Command\AbstractCommand;

/**
 * Class PerformAutomaticScoringCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class PerformAutomaticScoringCommand extends AbstractCommand {
    /**
     * @var string
     */
    public $result_uuid;
    
    /**
     * @param string $result_uuid
     * @param int $user_id
     */
    public function __construct(string $result_uuid, int $user_id) {
        $this->result_uuid = $result_uuid;
        parent::__construct($user_id);
    }
    
    /**
     * @return string
     */
    public function getResultUuid() : string
    {
        return $this->result_uuid;
    }
}