<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Section\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSection;
use Fluxlabs\Assessment\Test\Domain\Section\Model\AssessmentSectionRepository;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

/**
 * Class AddItemCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AddItemCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command AddItemCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentSectionRepository();

        /** @var $section AssessmentSection */
        $section = $repo->getAggregateRootById($command->getSectionId());

        $section->addItem($command->getItem(), $command->getIssuingUserId());

        $repo->save($section);

        return new Ok(null);
    }
}
