<?php
declare(strict_types=1);

namespace srag\asq\Test\Infrastructure\Setup\sql;

use srag\asq\Test\Domain\Result\Persistence\AssessmentResultEventStoreAr;
use srag\asq\Test\Domain\Section\Persistence\AssessmentSectionEventStoreAr;

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
        AssessmentSectionEventStoreAr::updateDB();
    }
    
    public static function uninstall() : void
    {
        global $DIC;
        
        $DIC->database()->dropTable(AssessmentResultEventStoreAr::STORAGE_NAME, false);
        $DIC->database()->dropTable(AssessmentSectionEventStoreAr::STORAGE_NAME, false);
    }
}
