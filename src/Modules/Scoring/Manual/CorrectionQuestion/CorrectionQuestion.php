<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Scoring\Manual\CorrectionQuestion;

use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemResult;
use Fluxlabs\Assessment\Test\Modules\Scoring\Manual\CorrectionPage;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\KitchenSinkTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use ilTemplate;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class CorrectionQuestion
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CorrectionQuestion
{
    use PathHelper;
    use KitchenSinkTrait;
    use LanguageTrait;
    use CtrlTrait;

    private ItemResult $result;

    private AsqServices $services;

    public function __construct(ItemResult $result) {
        $this->result = $result;

        global $ASQDIC;
        $this->services = $ASQDIC->asq();
    }

    public function render() : string
    {
        $question = $this->services->question()->getQuestionByQuestionId($this->result->getQuestionId());
        $answer = $this->result->getAnswer();

        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Scoring/Manual/CorrectionQuestion/CorrectionQuestion.html', true, true);

        $question_control = $this->services->ui()->getQuestionComponent($question)->withDisabled(true);

        if ($answer !== null) {
            $question_control = $question_control->withAnswer($this->result->getAnswer());
        }

        $tpl->setVariable('QUESTION', $this->renderKSComponent($question_control));

        $tpl->setVariable('MAX_SCORE', $this->txt('asqt_max_score') . ': ' . $this->services->answer()->getMaxScore($question));

        try {
            $tpl->setVariable('AUTOMATIC_SCORE', $this->txt('asqt_auto_score') . ': ' . ($answer ? $this->services->answer()->getScore($question, $answer) : 0));
        }
        catch (AsqException $exception) {
            $tpl->setVariable('AUTOMATIC_SCORE', $this->txt('asqt_auto_score_impossible'));
        }

        $tpl->setVariable('CUSTOM_SCORING', $this->renderCustomScoring());

        return $tpl->get();
    }

    private function renderCustomScoring() : string
    {
        $this->setLinkParameter(CorrectionPage::PARAM_QUESTION_CORRECTION_ID, $this->result->getQuestionId()->toString());

        $submit_button = sprintf(
            '<button class="btn btn-default" formmethod="post" formaction="%s">%s</button>',
            $this->getCommandLink(CorrectionPage::CMD_SET_QUESTION_SCORE),
            $this->txt('asqt_submit_score')
        );

        return sprintf(
            '<label>%s</label> <input type="text" name="%s" value="%s" />%s',
            $this->txt('asqt_custom_score') . ':',
            CorrectionPage::PARAM_QUESTION_SCORE . $this->result->getQuestionId()->toString(),
            $this->result->getScore() ? $this->result->getScore()->getReachedScore() : '',
            $submit_button
        );
    }
}