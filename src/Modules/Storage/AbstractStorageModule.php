<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Storage;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IStorageModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\asq\Test\Lib\Event\Event;

/**
 * Abstract Class AbstractStorageModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractStorageModule extends AbstractTestModule implements IStorageModule
{
    public function getType(): string
    {
        return ITestModule::TYPE_STORAGE;
    }
}