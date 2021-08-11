<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Objects;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Abstract Class ObjectConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class ObjectConfiguration extends AbstractValueObject
{
    /**
     * The name of the module that processes this configuration into and object
     *
     * @return string
     */
    abstract public function moduleName() : string;
}