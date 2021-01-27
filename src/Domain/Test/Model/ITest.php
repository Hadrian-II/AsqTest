<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Model;

use srag\asq\Test\Domain\Test\Persistence\TestType;

/**
 * Interface Test
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
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
     * Gets all Modules used by the Test
     *
     * @return ITestModule[]
     */
    public function getModules() : array;
}