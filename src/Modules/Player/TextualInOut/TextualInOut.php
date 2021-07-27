<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\TextualInOut;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;

/**
 * Class TextualInOut
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TextualInOut extends AbstractTestModule
{
    public function getType(): string
    {
        return ITestModule::TYPE_PLAYER;
    }

    public function getConfigClass() : ?string
    {
        return TextualInOutConfigurationFactory::class;
    }
}