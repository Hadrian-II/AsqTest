<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Section\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Section\Model\AssessmentSection;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionRepository;
use ILIAS\Data\Result\Ok;
use ILIAS\Data\Result;

/**
 * Class AddItemCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RemoveItemCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command RemoveItemCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentSectionRepository();

        /** @var $section AssessmentSection */
        $section = $repo->getAggregateRootById($command->getSectionId());
        $section->removeItem($command->getItem(), $command->getIssuingUserId());


        $repo->save($section);

        return new Ok(null);
    }
}
