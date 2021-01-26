<?php

namespace srag\asq\Test\Modules\Scoring\Automatic;

use srag\asq\Test\Domain\Test\Model\AbstractTestModule;
use srag\asq\Test\Domain\Test\Model\ITestModule;
/**
 * Class AutomaticScoring
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AutomaticScoring extends AbstractTestModule
{
    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_SCORING;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\AbstractTestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return AutomaticScoringConfiguration::class;
    }
}