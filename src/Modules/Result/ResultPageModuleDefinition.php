<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Result;

use Fluxlabs\Assessment\Tools\Domain\Modules\Access\AccessConfiguration;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\CommandDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\TabDefinition;

/**
 * Class ResultPageModuleDefinition
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ResultPageModuleDefinition extends ModuleDefinition
{
    public function __construct()
    {
        parent::__construct(
            ModuleDefinition::NO_CONFIG,
            [
                new CommandDefinition(
                    ResultPage::CMD_SHOW_RESULTS,
                    AccessConfiguration::ACCESS_STAFF,
                    ResultPage::RESULT_TAB
                )
            ],
            [],
            [
                new TabDefinition(
                    ResultPage::RESULT_TAB,
                    'asqt_results',
                    ResultPage::CMD_SHOW_RESULTS
                )
            ]
        );
    }
}