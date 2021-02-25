<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Test\Command;

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


        return new Ok(null);
    }
}
