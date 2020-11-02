<?php

namespace srag\asq\Test\Leipzig;

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
    public function getTestType() : TestType
    {
        return TestType::create('aqtl', 'Test für Leipzig', '', '', '', '', self::class);
    }
}