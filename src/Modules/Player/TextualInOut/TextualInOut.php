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
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TextualInOut extends AbstractTestModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): string
    {
        return ITestModule::TYPE_PLAYER;
    }

    /**
     * {@inheritDoc}
     * @see AbstractTestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return TextualInOutConfigurationFactory::class;
    }
}