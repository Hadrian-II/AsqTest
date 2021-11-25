<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page\Buttons;

use Fluxlabs\Assessment\Test\Modules\Player\IPlayerContext;
use Fluxlabs\Assessment\Test\Modules\Player\Page\PlayerPage;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
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
    use LanguageTrait;

    private IPlayerContext $context;

    public function __construct(IPlayerContext $context)
    {
        $this->context = $context;
    }

    public function render() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Player/Page/Buttons/PlayerButtons.html', true, true);

        if ($this->context->hasPreviousQuestion())
        {
            $this->setLinkParameter(
                PlayerPage::PARAM_DESTINATION_QUESTION,
                $this->context->getPreviousQuestion()->toString());

            $tpl->setCurrentBlock('previous');
            $tpl->setVariable('PREV_ACTION', $this->getCommandLink(PlayerPage::CMD_GOTO_QUESTION));
            $tpl->setVariable('PREV_TEXT', $this->txt('asqt_previous_question'));
            $tpl->parseCurrentBlock();
        }

        if ($this->context->hasNextQuestion())
        {
            $this->setLinkParameter(
                PlayerPage::PARAM_DESTINATION_QUESTION,
                $this->context->getNextQuestion()->toString());

            $tpl->setCurrentBlock('next');
            $tpl->setVariable('NEXT_ACTION', $this->getCommandLink(PlayerPage::CMD_GOTO_QUESTION));
            $tpl->setVariable('NEXT_TEXT', $this->txt('asqt_next_question'));
            $tpl->parseCurrentBlock();
        }

        $tpl->setVariable('SAVE_ACTION', $this->getCommandLink(PlayerPage::CMD_STORE_ANSWER));
        $tpl->setVariable('SAVE_TEXT', $this->txt('asqt_save_answer'));

        $tpl->setVariable('SUBMIT_ACTION', $this->getCommandLink(PlayerPage::CMD_SUBMIT_TEST));
        $tpl->setVariable('SUBMIT_TEXT', $this->txt('asqt_submit_test'));

        return $tpl->get();
    }
}