<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Command;

use Fluxlabs\CQRS\Command\CommandContract;
use Fluxlabs\CQRS\Command\CommandHandlerContract;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTest;
use Fluxlabs\Assessment\Test\Domain\Test\Model\AssessmentTestRepository;

/**
 * Class CreateTestCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CreateTestCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command CreateTestCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $test = AssessmentTest::createNewTest(
            $command->getId(),
            $command->getIssuingUserId(),
        );

        $repo = new AssessmentTestRepository();
        $repo->save($test);

        return new Ok(null);
    }
}
