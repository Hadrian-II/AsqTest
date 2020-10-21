<?php

namespace srag\asq\Test\Domain\Test\Persistence;

use srag\CQRS\Event\AbstractStoredEvent;

/**
 * Class TestEventStoreAr
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TestEventStoreAr extends AbstractStoredEvent
{
    const STORAGE_NAME = "asq_test_es";

    /**
     * @return string
     */
    public static function returnDbTableName()
    {
        return self::STORAGE_NAME;
    }
}
