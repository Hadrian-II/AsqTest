<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page\Buttons;

use Fluxlabs\Assessment\Test\Modules\Player\IPlayerContext;
use Fluxlabs\Assessment\Test\Modules\Player\Page\PlayerPage;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use ilTemplate;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class PlayerButtons
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class PlayerButtons
{
    use PathHelper;
    use CtrlTrait;

    private IPlayerContext $context;

    public function __construct(IPlayerContext $context)
    {
        $this->context = $context;
    }

    public function render() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Player/Page/Buttons/PlayerButtons.html', true, true);

        $this->setLinkParameter(
            PlayerPage::PARAM_CURRENT_QUESTION,
            $this->context->getCurrentQuestion()->getId()->toString());

        if ($this->context->hasPreviousQuestion())
        {
            $this->setLinkParameter(
                PlayerPage::PARAM_DESTINATION_QUESTION,
                $this->context->getPreviousQuestion()->toString());

            $tpl->setCurrentBlock('previous');
            $tpl->setVariable('PREV_ACTION', $this->getCommandLink(PlayerPage::CMD_GOTO_QUESTION));
            $tpl->setVariable('PREV_TEXT', 'TODO Previous Question');
            $tpl->parseCurrentBlock();
        }

        if ($this->context->hasNextQuestion())
        {
            $this->setLinkParameter(
                PlayerPage::PARAM_DESTINATION_QUESTION,
                $this->context->getNextQuestion()->toString());

            $tpl->setCurrentBlock('previous');
            $tpl->setVariable('NEXT_ACTION', $this->getCommandLink(PlayerPage::CMD_GOTO_QUESTION));
            $tpl->setVariable('NEXT_TEXT', 'TODO Next Question');
            $tpl->parseCurrentBlock();
        }

        $tpl->setVariable('SAVE_ACTION', $this->getCommandLink(PlayerPage::CMD_STORE_ANSWER));
        $tpl->setVariable('SAVE_TEXT', 'TODO Save Answer');

        $tpl->setVariable('SUBMIT_ACTION', $this->getCommandLink(PlayerPage::CMD_SUBMIT_TEST));
        $tpl->setVariable('SUBMIT_TEXT', 'TODO Submit Test');

        return $tpl->get();
    }
}