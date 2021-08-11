<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\asq\Test\Domain\Test\Persistence\TestType;
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
     * @param string $class
     * @return ITestModule
     */
    public function getModule(string $class) : ITestModule;

    /**
     * Gets an object from the Test
     *
     * @param string $key
     * @return ITestObject
     */
    public function getObject(string $key) : ITestObject;

    /**
     * Gets all objects of a type defined in ITestModule
     *
     * @param string $type
     * @return ITestObject[]
     */
    public function getObjectsOfType(string $type) : array;

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