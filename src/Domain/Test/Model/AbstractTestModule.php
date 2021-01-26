<?php

namespace srag\asq\Test\Domain\Test\Model;

/**
 * Abstract class AbstractTestModule
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
abstract class AbstractTestModule implements  ITestModule
{
    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getConfigType()
     */
    public function getConfigType() : ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return null;
    }
}