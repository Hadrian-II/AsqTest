<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ITestObject;

/**
 * Interface ITestAccess
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ITestAccess
{
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
}