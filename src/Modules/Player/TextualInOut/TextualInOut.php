<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\TextualInOut;

use srag\asq\Test\Domain\Test\Model\AbstractTestModule;
use srag\asq\Test\Domain\Test\Model\ITestModule;

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
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_PLAYER;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\AbstractTestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return TextualInOutConfiguration::class;
    }
}