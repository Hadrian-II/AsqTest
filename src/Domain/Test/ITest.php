<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\omain;

use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Persistence\TestType;
use ILIAS\Data\Result;

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

    /**
     * @return Result
     */
    public function onBeforeEvent() : Result;

    /**
     * @return Result
     */
    public function onEvent() : Result;

    /**
     * @return Result
     */
    public function onPostEvent() : Result;
}