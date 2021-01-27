<?php
declare(strict_types = 1);

namespace srag\asq\Test\Leipzig;

use srag\asq\Test\Domain\Test\Model\ITestModule;
use srag\asq\Test\Domain\Test\Persistence\TestType;

/**
 * Class LeipzigTest
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class LeipzigTest
{
    /**
     * @return TestType
     */
    public function getTestType() : TestType
    {
        return new TestType('aqtl', 'Test für Leipzig', self::class);
    }

    /**
     * @return ITestModule[]
     */
    public function getModules() : array
    {
        return [

        ];
    }
}