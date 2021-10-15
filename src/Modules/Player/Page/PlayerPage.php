<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page;

use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\AddTabEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\UI\System\TabDefinition;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ilTemplate;

/**
 * Class PlayerPage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class PlayerPage extends AbstractAsqModule implements IPageModule
{
    const CMD_PREVIOUS_QUESTION = 'previousQuestion';
    const CMD_NEXT_QUESTION = 'nextQuestion';
    const CMD_SHOW_TEST = 'showTest';
    const CMD_SUBMIT_TEST = 'submitTest';
    const CMD_GET_HINT = 'getHint';

    const PARAM_CURRENT_RESULT = 'currentResult';
    const PARAM_CURRENT_QUESTION = 'currentQuestion';

    public function __construct(IEventQueue $event_queue, IObjectAccess $access)
    {
        parent::__construct($event_queue, $access);

        $this->raiseEvent(new AddTabEvent(
            $this,
            new TabDefinition(self::class, 'Questions', self::CMD_SHOW_TEST)
        ));
    }

    public function showTest() : void
    {
        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Test',
            $this->renderContent()
        )));
    }

    public function renderContent() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Player/Page/PlayerPage.html', true, true);

        $tpl->setVariable('',);
        $tpl->setVariable('',);

        return $tpl->get();
    }

    public function getCommands(): array
    {
        return [
            self::CMD_SHOW_TEST,
            self::CMD_NEXT_QUESTION,
            self::CMD_PREVIOUS_QUESTION,
            self::CMD_GET_HINT,
            self::CMD_SUBMIT_TEST
        ];
    }
}