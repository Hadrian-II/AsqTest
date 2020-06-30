<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use srag\CQRS\Command\AbstractCommand;
use srag\asq\Test\Domain\Result\Model\AssessmentResultContext;

/**
 * Class StartAssessmentCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class StartAssessmentCommand extends AbstractCommand
{
    /**
     * @var AssessmentResultContext
     */
    protected $context;
    
    /**
     * @var string[]
     */
    protected $question_ids;

    /**
     * @var string
     */
    protected $uuid;
    
    /**
     * @param int $user_id
     * @param AssessmentResultContext $context
     * @param array $question_ids
     */
    public function __construct(string $uuid, int $user_id, AssessmentResultContext $context, array $question_ids)
    {
        $this->uuid = $uuid;
        $this->context = $context;
        $this->question_ids = $question_ids;
        parent::__construct($user_id);
    }
   
    public function getUuid() : string
    {
        return $this->uuid;
    }
    
    /**
     * @return AssessmentResultContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string[]
     */
    public function getQuestionIds() : array
    {
        return $this->question_ids;
    }
}
