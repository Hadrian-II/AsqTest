<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Section\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Section\Model\AssessmentSection;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionRepository;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

/**
 * Class AddItemCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddItemCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command AddItemCommand
     */
    public function handle(CommandContract $command) : Result
    {
        /** @var $section AssessmentSection */
        $section = AssessmentSectionRepository::getInstance()->getAggregateRootById($command->getSectionId());

        $section->addItem($command->getItem(), $command->getIssuingUserId());

        AssessmentSectionRepository::getInstance()->save($section);

        return new Ok(null);
    }
}
