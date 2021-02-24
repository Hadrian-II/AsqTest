<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Scoring\Automatic;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IScoringModule;

/**
 * Class AutomaticScoring
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AutomaticScoring extends AbstractTestModule implements IScoringModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_SCORING;
    }

    /**
     * {@inheritDoc}
     * @see ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return AutomaticScoringConfigurationFactory::class;
    }
}