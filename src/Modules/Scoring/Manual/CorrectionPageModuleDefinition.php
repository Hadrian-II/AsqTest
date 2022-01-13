<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Scoring\Manual;

use Fluxlabs\Assessment\Tools\Domain\Modules\Access\AccessConfiguration;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\CommandDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\TabDefinition;

/**
 * Class CorrectionPageModuleDefinition
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CorrectionPageModuleDefinition extends ModuleDefinition
{
    public function __construct()
    {
        parent::__construct(
            ModuleDefinition::NO_CONFIG,
            [
                new CommandDefinition(
                    CorrectionPage::CMD_SHOW_CORRECTIONS,
                    AccessConfiguration::ACCESS_STAFF,
                    CorrectionPage::CORRECTION_TAB
                ),
                new CommandDefinition(
                    CorrectionPage::CMD_SET_QUESTION_SCORE,
                    AccessConfiguration::ACCESS_STAFF,
                    CorrectionPage::CORRECTION_TAB
                ),
                new CommandDefinition(
                    CorrectionPage::CMD_SUBMIT_CORRECTION,
                    AccessConfiguration::ACCESS_STAFF,
                    CorrectionPage::CORRECTION_TAB
                )
            ],
            [],
            [
                new TabDefinition(
                    CorrectionPage::CORRECTION_TAB,
                    'asqt_correction',
                    CorrectionPage::CMD_SHOW_CORRECTIONS
                )
            ]
        );
    }
}