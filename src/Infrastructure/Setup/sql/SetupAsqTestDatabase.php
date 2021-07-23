<?php
declare(strict_types=1);

namespace srag\asq\Test\Infrastructure\Setup\sql;

use srag\asq\Test\Domain\Result\Persistence\AssessmentResultEventStoreAr;
use srag\asq\Test\Domain\Section\Persistence\AssessmentSectionEventStoreAr;
use srag\asq\Test\Domain\Test\Persistence\AssessmentTestEventStoreAr;

/**
 * Class SetupAsqTestDatabase
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SetupAsqTestDatabase
{
    public static function run() : void
    {
        AssessmentResultEventStoreAr::updateDB();
        AssessmentSectionEventStoreAr::updateDB();
        AssessmentTestEventStoreAr::updateDB();
    }

    public static function uninstall() : void
    {
        global $DIC;

        $DIC->database()->dropTable(AssessmentResultEventStoreAr::STORAGE_NAME, false);
        $DIC->database()->dropTable(AssessmentSectionEventStoreAr::STORAGE_NAME, false);
        $DIC->database()->dropTable(AssessmentTestEventStoreAr::STORAGE_NAME, false);
    }
}
