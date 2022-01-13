<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Page;

use Fluxlabs\Assessment\Tools\Domain\Modules\Access\AccessConfiguration;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\CommandDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\TabDefinition;

/**
 * Class QuestionPageModuleDefinition
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPageModuleDefinition extends ModuleDefinition
{
    public function __construct()
    {
        parent::__construct(
            ModuleDefinition::NO_CONFIG,
            [
                new CommandDefinition(
                    QuestionPage::CMD_SHOW_QUESTIONS,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                ),
                new CommandDefinition(
                    QuestionPage::CMD_REMOVE_SOURCE,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                ),
                new CommandDefinition(
                    QuestionPage::CMD_INITIALIZE_TEST,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                ),
                new CommandDefinition(
                    QuestionPage::CMD_SELECT_QUESTIONS,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                )
            ],
            [],
            [
                new TabDefinition(
                    QuestionPage::QUESTION_TAB,
                    'asqt_questions',
                QuestionPage::CMD_SHOW_QUESTIONS)
            ]
        );
    }
}