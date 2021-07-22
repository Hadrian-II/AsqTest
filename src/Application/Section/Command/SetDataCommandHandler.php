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
 * Class SetDataCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SetDataCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command SetDataCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentSectionRepository();

        /** @var $section AssessmentSection */
        $section = $repo->getAggregateRootById($command->getSectionId());

        $section->setData($command->getData(), $command->getIssuingUserId());

        $repo->save($section);

        return new Ok(null);
    }
}
