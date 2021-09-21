<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Command;

use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTestRepository;
use Fluxlabs\CQRS\Command\CommandContract;
use Fluxlabs\CQRS\Command\CommandHandlerContract;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

/**
 * Class AddSectionCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
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
