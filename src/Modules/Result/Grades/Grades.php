<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Result\Grades;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IResultModule;

/**
 * Class Grades
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class Grades extends AbstractTestModule implements IResultModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_RESULT;
    }

    /**
     * {@inheritDoc}
     * @see AbstractTestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return GradesConfiguration::class;
    }
}