<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page;

use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\RunManager;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\CommandDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\TabDefinition;

/**
 * Class PlayerPageModuleDefinition
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class PlayerPageModuleDefinition extends ModuleDefinition
{
    public function __construct()
    {
        parent::__construct(
            PlayerConfigurationFactory::class,
            [
                new CommandDefinition(
                    PlayerPage::CMD_SHOW_TEST,
                    CommandDefinition::ACCESS_MEMBER,
                    PlayerPage::PLAYER_TAB
                ),
                new CommandDefinition(
                    PlayerPage::CMD_GOTO_QUESTION,
                    CommandDefinition::ACCESS_MEMBER,
                    PlayerPage::PLAYER_TAB
                ),
                new CommandDefinition(
                    PlayerPage::CMD_STORE_ANSWER,
                    CommandDefinition::ACCESS_MEMBER,
                    PlayerPage::PLAYER_TAB
                ),
                new CommandDefinition(
                    PlayerPage::CMD_GET_HINT,
                    CommandDefinition::ACCESS_MEMBER,
                    PlayerPage::PLAYER_TAB
                ),
                new CommandDefinition(
                    PlayerPage::CMD_SUBMIT_TEST,
                    CommandDefinition::ACCESS_MEMBER,
                    PlayerPage::PLAYER_TAB
                )
            ],
            [
                RunManager::class
            ],
            [
                new TabDefinition(
                    PlayerPage::PLAYER_TAB,
                    'asqt_test',
                    PlayerPage::CMD_SHOW_TEST,
                    TabDefinition::PRIORITY_HIGH)
            ]
        );
    }
}