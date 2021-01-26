<?php

namespace srag\asq\Test\Modules\Player\QuestionDisplay;

use srag\asq\Test\Domain\Test\Model\AbstractTestModule;
use srag\asq\Test\Domain\Test\Model\ITestModule;
/**
 * Class QuestionDisplay
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionDisplay extends AbstractTestModule
{
    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_PLAYER;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return QuestionDisplayConfiguration::class;
    }
}