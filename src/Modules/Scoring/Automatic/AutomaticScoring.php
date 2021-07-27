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
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AutomaticScoring extends AbstractTestModule implements IScoringModule
{
    public function getType(): string
    {
        return ITestModule::TYPE_SCORING;
    }

    public function getConfigClass() : ?string
    {
        return AutomaticScoringConfigurationFactory::class;
    }
}