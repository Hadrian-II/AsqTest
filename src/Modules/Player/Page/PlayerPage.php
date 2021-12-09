<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page;

use Fluxlabs\Assessment\Test\Modules\Player\IPlayerContext;
use Fluxlabs\Assessment\Test\Modules\Player\Page\Buttons\PlayerButtons;
use Fluxlabs\Assessment\Test\Modules\Player\Page\QuestionDisplay\QuestionDisplay;
use Fluxlabs\Assessment\Test\Modules\Player\Page\QuestionDisplay\QuestionDisplayConfigurationFactory;
use Fluxlabs\Assessment\Test\Modules\Player\Page\TestOverview\TestOverview;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\SubmitTestEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\Event\StoreAnswerEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\RunManager;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\KitchenSinkTrait;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\AddTabEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\UI\System\TabDefinition;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use ilTemplate;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class PlayerPage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class PlayerPage extends AbstractAsqModule implements IPageModule
{
    use PathHelper;
    use CtrlTrait;
    use KitchenSinkTrait;

    const CMD_GOTO_QUESTION = 'gotoQuestion';
    const CMD_STORE_ANSWER = 'storeAnswer';
    const CMD_SHOW_TEST = 'showTest';
    const CMD_SUBMIT_TEST = 'submitTest';
    const CMD_GET_HINT = 'getHint';

    const PARAM_CURRENT_QUESTION = 'currentQuestion';
    const PARAM_DESTINATION_QUESTION = 'destinationQuestion';

    private IPlayerContext $context;

    private Factory $uuid_factory;

    private AsqServices $asq;

    public function __construct(IEventQueue $event_queue, IObjectAccess $access)
    {
        parent::__construct($event_queue, $access);

        global $ASQDIC;
        $this->asq = $ASQDIC->asq();

        $this->uuid_factory = new Factory();

        $this->raiseEvent(new AddTabEvent(
            $this,
            new TabDefinition(self::class, 'Test', self::CMD_SHOW_TEST)
        ));
    }

    public function showTest() : void
    {
        $raw_current_id = $this->getLinkParameter(self::PARAM_CURRENT_QUESTION);

        $current_question_id = null;
        if ($raw_current_id !== null) {
            $current_question_id = $this->uuid_factory->fromString($raw_current_id);
        }

        $this->renderQuestion($current_question_id);
    }

    public function renderQuestion(?Uuid $question_id) : void
    {
        $this->context = $this->access->getModule(RunManager::class)->getPlayerContext($question_id);

        $this->setLinkParameter(
            PlayerPage::PARAM_CURRENT_QUESTION,
            $this->context->getCurrentQuestion()->getId()->toString());

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Test',
            $this->renderContent()
        )));
    }

    public function renderContent() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Player/Page/PlayerPage.html', true, true);

        $question = new QuestionDisplay(
            $this->context->getCurrentQuestion(),
            $this->context->getAnswer(),
            $this->getModuleConfiguration()->getConfiguration(QuestionDisplayConfigurationFactory::class)
        );
        $tpl->setVariable('QUESTION', $question->render());

        $overview = new TestOverview($this->context);
        $tpl->setVariable('OVERVIEW', $overview->render());

        $buttons = new PlayerButtons($this->context);
        $tpl->setVariable('BUTTONS', $buttons->render());

        return $tpl->get();
    }

    public function storeAnswer() : void
    {
        $current_question_id = $this->getCurrentQuestionId();

        $this->saveAnswer($current_question_id);

        $this->renderQuestion($current_question_id);
    }

    public function gotoQuestion() : void
    {
        $raw_destination_id = $this->getLinkParameter(self::PARAM_DESTINATION_QUESTION);
        $destination_question_id = $this->uuid_factory->fromString($raw_destination_id);

        $this->saveAnswer($this->getCurrentQuestionId());

        $this->renderQuestion($destination_question_id);
    }

    private function saveAnswer(Uuid $question_id) : void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            //dont save on browser refresh
            return;
        }

        $component = $this->asq->ui()->getQuestionComponent(
            $this->asq->question()->getQuestionByQuestionId($question_id)
        );
        $component = $component->withAnswerFromPost();
        $answer = $component->getAnswer();

        $this->raiseEvent(new StoreAnswerEvent($this, $question_id, $answer));
    }

    public function submitTest() : void
    {


        $this->saveAnswer($this->getCurrentQuestionId());

        $this->raiseEvent(new SubmitTestEvent($this));

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Test',
            'Thanks for submitting'
        )));
    }

    private function getCurrentQuestionId() : Uuid
    {
        $raw_current_id = $this->getLinkParameter(self::PARAM_CURRENT_QUESTION);
        return $this->uuid_factory->fromString($raw_current_id);
    }

    public function getConfigFactory() : ?AbstractObjectFactory
    {
        global $DIC;

        return new PlayerConfigurationFactory($DIC->language(), $DIC->ui(), $this->asq->ui());
    }

    public function getCommands(): array
    {
        return [
            self::CMD_SHOW_TEST,
            self::CMD_GOTO_QUESTION,
            self::CMD_STORE_ANSWER,
            self::CMD_GET_HINT,
            self::CMD_SUBMIT_TEST
        ];
    }
}