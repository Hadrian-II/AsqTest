<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page\TestOverview;

use Fluxlabs\Assessment\Test\Modules\Player\IOverviewProvider;
use Fluxlabs\Assessment\Test\Modules\Player\Page\PlayerPage;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use ilTemplate;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class TestOverview
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestOverview
{
    use PathHelper;
    use CtrlTrait;
    use LanguageTrait;

    private IOverviewProvider $context;

    public function __construct(IOverviewProvider $context)
    {
        $this->context = $context;
    }

    public function render() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Player/Page/TestOverview/TestOverview.html', true, true);

        foreach($this->context->getOverview() as $item) {
            $tpl->setCurrentBlock('question');

            $this->setLinkParameter(
                PlayerPage::PARAM_DESTINATION_QUESTION,
                $item->getQuestionId()->toString());
            $tpl->setVariable('LINK', $this->getCommandLink(PlayerPage::CMD_GOTO_QUESTION));
            $tpl->setVariable('TITLE', $item->getText());

            $tpl->setVariable('ICON', $this->getIcon($item->getState()));
            $tpl->setVariable('STATE', $this->getStateString($item->getState()));
            $tpl->parseCurrentBlock();
        }

        return $tpl->get();
    }

    private function getIcon(int $state) : string
    {
        switch ($state) {
            case OverviewState::STATE_OPEN:
                return $this->getBasePath(__DIR__) . 'src/Modules/Player/Page/TestOverview/square.svg';
            case OverviewState::STATE_ANSWERED:
                return $this->getBasePath(__DIR__) . 'src/Modules/Player/Page/TestOverview/ok.svg';
        }

        throw new AsqException('Unimplemented Testoverviewstate');
    }

    private function getStateString(int $state) : string
    {
        switch ($state) {
            case OverviewState::STATE_OPEN:
                return $this->txt('asqt_open');
            case OverviewState::STATE_ANSWERED:
                return $this->txt('asqt_answered');
        }

        throw new AsqException('Unimplemented Testoverviewstate');
    }
}