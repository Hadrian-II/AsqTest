<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Test\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use srag\asq\Test\Domain\Test\Model\AssessmentTest;
use srag\asq\Test\Domain\Test\Model\AssessmentTestRepository;

/**
 * Class CreateTestCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
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
