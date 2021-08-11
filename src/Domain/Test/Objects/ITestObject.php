<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Objects;

/**
 * Interface ITestObject
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ITestObject
{
    public function getKey() : string;

    public function getConfiguration() : ObjectConfiguration;
}