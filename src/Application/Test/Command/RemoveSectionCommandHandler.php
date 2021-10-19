<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Command;

use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTestRepository;
use Fluxlabs\CQRS\Command\CommandContract;
use Fluxlabs\CQRS\Command\CommandHandlerContract;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

/**
 * Class RemoveSectionCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RemoveSectionCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command RemoveSectionCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $repo = new AssessmentTestRepository();
        $test = $repo->getAggregateRootById($command->getId());
        $test->removeSection($command->getSectionId(), $command->getIssuingUserId());
        $repo->save($test);

        return new Ok(null);
    }
}
