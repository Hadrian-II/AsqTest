<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Test\Command;

use srag\asq\Test\Domain\Test\Model\AssessmentTestRepository;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

/**
 * Class AddSectionCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddSectionCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command AddSectionCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentTestRepository();
        $test = $repo->getAggregateRootById($command->getId());
        $test->addSection($command->getSectionId(), $command->getIssuingUserId());
        $repo->save($test);

        return new Ok(null);
    }
}
