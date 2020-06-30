<?php

namespace srag\asq\Test\Application\Section\Command;

use srag\CQRS\Command\AbstractCommand;
use srag\asq\Domain\Model\Answer\Answer;
use srag\asq\Test\Domain\Section\Model\SectionPart;

/**
 * Class AddItemCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddItemCommand extends AbstractCommand
{
    /**
     * @var string
     */
    public $section_id;
    
    /**
     * @var SectionPart
     */
    public $item;
    
    /**
     * @param string $assessment_name
     * @param string $question_id
     * @param Answer $answer
     */
    public function __construct(string $section_id, int $user_id, SectionPart $item)
    {
        $this->section_id = $section_id;
        $this->item = $item;
        parent::__construct($user_id);
    }
    
    /**
     * @return string
     */
    public function getSectionId() : string
    {
        return $this->section_id;
    }
    
    /**
     * @return SectionPart
     */
    public function getItem() : SectionPart
    {
        return $this->item;
    }
}
