<?php
declare(strict_types=1);

namespace Fluxlabs\Assessment\Test\Infrastructure\Setup\sql;

use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\AssessmentInstanceEventStoreAr;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\InstanceState;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\RunState;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\TestState;
use Fluxlabs\Assessment\Test\Domain\Result\Persistence\AssessmentResultEventStoreAr;
use Fluxlabs\Assessment\Test\Domain\Section\Persistence\AssessmentSectionEventStoreAr;
use Fluxlabs\Assessment\Test\Domain\Test\Persistence\AssessmentTestEventStoreAr;

/**
 * Class SetupAsqTestDatabase
 *
 * @package Fluxlabs\Assessment\Test
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
        AssessmentInstanceEventStoreAr::updateDB();

        InstanceState::updateDB();
        RunState::updateDB();
        TestState::updateDB();
    }

    public static function uninstall() : void
    {
        global $DIC;

        $DIC->database()->dropTable(AssessmentResultEventStoreAr::STORAGE_NAME, false);
        $DIC->database()->dropTable(AssessmentSectionEventStoreAr::STORAGE_NAME, false);
        $DIC->database()->dropTable(AssessmentTestEventStoreAr::STORAGE_NAME, false);
        $DIC->database()->dropTable(AssessmentInstanceEventStoreAr::STORAGE_NAME, false);

        $DIC->database()->dropTable(InstanceState::STORAGE_NAME, false);
        $DIC->database()->dropTable(RunState::STORAGE_NAME, false);
        $DIC->database()->dropTable(TestState::STORAGE_NAME, false);
    }
}
