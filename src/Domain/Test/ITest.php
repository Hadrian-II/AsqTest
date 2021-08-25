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
    function getTestType() : TestType;

    /**
     * Gets testModule of given Class
     *
     * @param string $class
     * @return ITestModule
     */
    function getModule(string $class) : ITestModule;

    /**
     * Gets an object from the Test
     *
     * @param string $key
     * @return ITestObject
     */
    function getObject(string $key) : ITestObject;

    /**
     * Gets all objects of a type defined in ITestModule
     *
     * @param string $type
     * @return ITestObject[]
     */
    function getObjectsOfType(string $type) : array;

    /**
     * Executes a command in the test
     *
     * @param string $command
     */
    function executeCommand(string $command) : void;

    /**
     * Gets access to the ui module of the test
     *
     * @return ITestUI
     */
    function ui() : ITestUI;
}