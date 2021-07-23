<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Persistence\TestType;
use ILIAS\Data\Result;
use srag\asq\Test\UI\System\ITestUI;

/**
 * Interface Test
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ITest
{
    /**
     * Get the Test type definition of the Test
     *
     * @return TestType
     */
    public function getTestType() : TestType;

    /**
     * Gets testModule of given Class
     *
     * @return ITestModule
     */
    public function getModule(string $class) : ITestModule;

    /**
     * Executes a command in the test
     *
     * @param string $command
     */
    public function executeCommand(string $command) : void;

    /**
     * Gets access to the ui modcule of the test
     *
     * @return ITestUI
     */
    public function ui() : ITestUI;
}