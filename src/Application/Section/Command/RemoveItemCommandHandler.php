<?php

namespace srag\asq\Test\Application\Section\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Section\Model\AssessmentSection;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionRepository;
use srag\CQRS\Aggregate\DomainObjectId;

/**
 * Class AddItemCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class RemoveItemCommandHandler implements CommandHandlerContract {
    /**
     * @param $command RemoveItemCommand
     */
    public function handle(CommandContract $command)
    {
        /** @var $section AssessmentSection */
        $section = AssessmentSectionRepository::getInstance()->getAggregateRootById(
            new DomainObjectId($command->getSectionId())
        );
        
        $section->removeItem($command->getItem(), $command->getIssuingUserId());
        
        AssessmentSectionRepository::getInstance()->save($section);
    }
}