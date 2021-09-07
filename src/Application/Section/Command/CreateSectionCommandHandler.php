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
 * Class StartAssessmentCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CreateSectionCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command CreateSectionCommand
     */
    public function handle(CommandContract $command) : Result
    {
        $section = AssessmentSection::create(
            $command->getId(),
            $command->getIssuingUserId()
        );

        $repo = new AssessmentSectionRepository();
        $repo->save($section);

        return new Ok(null);
    }
}
