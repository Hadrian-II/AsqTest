<?php

namespace srag\asq\Test\Application\Section\Command;

use srag\CQRS\Command\AbstractCommand;

/**
 * Class StartAssessmentCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class CreateSectionCommand extends AbstractCommand {    
    /**
     * @var string
     */
    protected $uuid;
    
    /**
     * @param string $uuid
     * @param int $user_id
     */
    public function __construct(string $uuid, int $user_id) {
        $this->uuid = $uuid;
        parent::__construct($user_id);
    }
    
    /**
     * @return string
     */
    public function getId(): string {
        return $this->uuid;
    }
}