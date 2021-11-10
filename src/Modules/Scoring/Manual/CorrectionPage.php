<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Scoring\Manual;

use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\RunState;
use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemScore;
use Fluxlabs\Assessment\Test\Modules\Scoring\Event\SetManualCorrectionEvent;
use Fluxlabs\Assessment\Test\Modules\Scoring\Event\SubmitCorrectionEvent;
use Fluxlabs\Assessment\Test\Modules\Scoring\Manual\CorrectionQuestion\CorrectionQuestion;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\RunManager;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\KitchenSinkTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\AddTabEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\UI\System\TabDefinition;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use ilTemplate;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\UserInterface\Web\PostAccess;

/**
 * Class CorrectionPage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CorrectionPage extends AbstractAsqModule implements IPageModule
{
    use LanguageTrait;
    use PathHelper;
    use CtrlTrait;
    use KitchenSinkTrait;
    use PostAccess;

    const PARAM_QUESTION_CORRECTION_ID = 'correctionQuestionId';
    const PARAM_RUN_ID = 'correctionRunId';
    const PARAM_QUESTION_SCORE = 'question_score_';

    const CMD_SHOW_CORRECTIONS = 'showCorrections';
    const CMD_SET_QUESTION_SCORE = 'setQuestionScore';
    const CMD_SUBMIT_CORRECTION = 'submitCorrection';

    private RunManager $manager;

    private Factory $factory;

    private ?Uuid $current_run_id = null;



    public function __construct(IEventQueue $event_queue, IObjectAccess $access)
    {
        parent::__construct($event_queue, $access);

        $this->manager = $access->getModule(RunManager::class);
        $this->factory = new Factory();

        $this->raiseEvent(new AddTabEvent(
            $this,
            new TabDefinition(self::class, $this->txt('asqt_correction'), self::CMD_SHOW_CORRECTIONS)
        ));
    }

    public function showCorrections() : void
    {
        $this->readCurrentRun();

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            $this->txt('asqt_correction'),
            $this->renderContent()
        )));
    }

    private function readCurrentRun(): void
    {
        $current_run_id = $this->getLinkParameter(self::PARAM_RUN_ID);
        if ($current_run_id !== null) {
            $this->current_run_id = $this->factory->fromString($current_run_id);
        }
    }

    private function renderContent() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Scoring/Manual/CorrectionPage.html', true, true);

        $tpl->setVariable('AVAILABLE_RUNS', $this->renderAvailableRuns());

        if ($this->current_run_id !== null)
        {
            $this->renderQuestions($tpl);

            $tpl->setVariable(
                'SUBMIT_BUTTON',
                $this->renderKSComponent(
                    $this->getKSFactory()->button()->standard(
                        $this->txt('asqt_submit_correction'),
                        $this->getCommandLink(self::CMD_SUBMIT_CORRECTION)
                    )
                )
            );
        }

        return $tpl->get();
    }

    public function renderAvailableRuns() : string
    {
        $runs = array_map(function (RunState $state) {
            $this->setLinkParameter(self::PARAM_RUN_ID, $state->getAggregateId()->toString());

            return $this->getKSFactory()->button()->shy(
                $state->getUserId() . ' ' . $state->getAggregateId()->toString(),
                $this->getCommandLink(self::CMD_SHOW_CORRECTIONS)
            );
        }, $this->manager->getCorrectableRuns());

        $selection = $this->getKSFactory()->dropdown()->standard($runs)->withLabel($this->txt('asqt_select'));

        return $this->renderKSComponent($selection);
    }

    public function renderQuestions(ilTemplate $tpl) : void
    {
        foreach ($this->manager->getResultsForCorrection($this->current_run_id) as $result)
        {
            $control = new CorrectionQuestion($result);

            $tpl->setCurrentBlock('question');
            $tpl->setVariable('QUESTION', $control->render());
            $tpl->parseCurrentBlock();
        }
    }

    public function setQuestionScore() : void
    {
        $this->readCurrentRun();

        if ($this->current_run_id === null) {
            throw new AsqException('Cant set correction score if no run is selected');
        }

        $question_id = $this->getLinkParameter(self::PARAM_QUESTION_CORRECTION_ID);
        $value = floatval($this->getPostValue(self::PARAM_QUESTION_SCORE . $question_id));

        $this->raiseEvent(new SetManualCorrectionEvent(
            $this,
            $this->current_run_id,
            $this->factory->fromString($question_id),
            new ItemScore(
                ItemScore::MANUAL_SCORING,
                $value
            )
        ));

        $this->raiseEvent(
            new ForwardToCommandEvent(
                $this,
                self::CMD_SHOW_CORRECTIONS,
                [
                    self::PARAM_RUN_ID => $this->current_run_id->toString()
                ])
        );
    }

    public function submitCorrection() : void
    {
        $this->readCurrentRun();

        if ($this->current_run_id === null) {
            throw new AsqException('Cant submit correction if no run is selected');
        }

        $this->raiseEvent(new SubmitCorrectionEvent($this, $this->current_run_id));

        $this->raiseEvent(
            new ForwardToCommandEvent($this, self::CMD_SHOW_CORRECTIONS)
        );
    }

    public function getCommands(): array
    {
        return [
            self::CMD_SHOW_CORRECTIONS,
            self::CMD_SET_QUESTION_SCORE,
            self::CMD_SUBMIT_CORRECTION
        ];
    }
}