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
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class Grades extends AbstractTestModule implements IResultModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): string
    {
        return ITestModule::TYPE_RESULT;
    }

    /**
     * {@inheritDoc}
     * @see AbstractTestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return GradesConfigurationFactory::class;
    }
}