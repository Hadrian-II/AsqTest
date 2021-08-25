<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Test\Domain\Test\Model\TestData;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Interface IStorageModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IStorageModule extends ITestModule
{
    /**
     * Gets the configuration object by object key
     *
     * @param string $configuration_for
     * @return AbstractValueObject|null
     */
    public function getConfiguration(string $configuration_for) : ?AbstractValueObject;

    /**
     * Gets all configurations
     *
     * @return AbstractValueObject[]
     */
    public function getConfigurations() : array;

    /**
     * Sets configuration by object key
     *
     * @param string $configuration_for
     * @param AbstractValueObject $config
     */
    public function setConfiguration(string $configuration_for, AbstractValueObject $config) : void;

    /**
     * Deletes configuration by key
     *
     * @param string $configuration_for
     */
    public function removeConfiguration(string $configuration_for) : void;

    /**
     * Stores Data
     */
    public function save() : void;
}