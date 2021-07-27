<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\QuestionDisplay;

use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IPlayerModule;

/**
 * Class QuestionDisplay
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDisplay extends AbstractTestModule implements IPlayerModule
{
    public function getType(): string
    {
        return ITestModule::TYPE_PLAYER;
    }

    public function getConfigClass() : ?string
    {
        return QuestionDisplayConfigurationFactory::class;
    }

    public function getNextQuestion(): ?QuestionDto
    {

    }

    public function getFirstQuestion(): QuestionDto
    {

    }

    public function getPreviousQuestion(): ?QuestionDto
    {

    }
}