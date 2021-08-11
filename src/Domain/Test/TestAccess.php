<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ITestObject;

/**
 * class TestAccess
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestAccess implements ITestAccess
{
    private ITest $test;

    public function __construct(ITest $test)
    {
        $this->test = $test;
    }

    public function getModule(string $class) : ITestModule
    {
        return $this->test->getModule($class);
    }

    public function getObject(string $key) : ITestObject
    {
        return $this->test->getObject($key);
    }

    public function getObjectsOfType(string $type) : array
    {
        return $this->test->getObjectsOfType($type);
    }
}