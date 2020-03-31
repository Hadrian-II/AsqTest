<?php
declare(strict_types=1);

namespace srag\asq\Test\Infrastructure\Setup\sql;

use srag\asq\Test\Infrastructure\Persistence\AssessmentResultEventStoreAr;

/**
 * Class SetupAsqTestDatabase
 *
 * @author Adrian Lüthi <ms@studer-raimann.ch>
 */
class SetupAsqTestDatabase
{
    public static function run() : void
    {
        AssessmentResultEventStoreAr::updateDB();
    }
}