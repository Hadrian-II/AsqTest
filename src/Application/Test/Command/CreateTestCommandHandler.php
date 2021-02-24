<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Test\Command;

use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;

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
        return new Ok(null);
    }
}
