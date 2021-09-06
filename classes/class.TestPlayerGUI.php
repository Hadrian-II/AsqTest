<?php

use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\QuestionDto;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\Test\Application\TestRunner\TestRunnerService;
use srag\asq\Test\Domain\Result\Model\ItemResult;
use srag\asq\UserInterface\Web\Component\Hint\HintComponent;

/**
 * Class TestPlayerGUI
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestPlayerGUI
{
    use PathHelper;
    use CtrlTrait;

    const LANG_TEST = 'test';

    const CMD_PREVIOUS_QUESTION = 'previousQuestion';
    const CMD_NEXT_QUESTION = 'nextQuestion';
    const CMD_RUN_TEST = 'runTest';
    const CMD_SHOW_RESULTS = 'showResults';
    const CMD_GET_HINT = 'getHint';
    const PARAM_CURRENT_RESULT = 'currentResult';
    const PARAM_CURRENT_QUESTION = 'currentQuestion';

    private Uuid $result_id;

    private QuestionDto $question;

    private TestRunnerService $test_service;

    private ?ItemResult $item_result;

    private Factory $factory;

    public function __construct()
    {
        global $DIC;

        $this->test_service = new TestRunnerService();
        $this->factory = new Factory();
        $DIC->language()->loadLanguageModule('asqt');

        $this->result_id = $this->factory->fromString($_GET[self::PARAM_CURRENT_RESULT]);
        $this->setLinkParameter(self::PARAM_CURRENT_RESULT, $this->result_id->toString());

        if (is_null($this->result_id) || empty($this->result_id)) {
            throw new AsqException('AssessmentResult id not set (PARAM_CURRENT_RESULT)');
        }
    }

    public function executeCommand() : void
    {
        global $DIC;

        $this->loadQuestion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->storeAnswer();
        }

        $cmd = $DIC->ctrl()->getCmd();
        $this->{$cmd}();
    }

    private function runTest() : void
    {
        global $DIC, $ASQDIC;

        $component = $ASQDIC->asq()->ui()->getQuestionComponent($this->question);

        $this->item_result = $this->test_service->getItemResult($this->result_id, $this->question->getId());
        if (!is_null($this->item_result) && !is_null($this->item_result->getAnswer())) {
            $component = $component->withAnswer($this->item_result->getAnswer());
        }

        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'templates/default/tpl.test_player.html', true, true);
        $tpl->setVariable('FORM_ACTION', $DIC->ctrl()->getFormAction($this, self::CMD_RUN_TEST));
        $tpl->setVariable('QUESTION_COMPONENT', $DIC->ui()->renderer()->render($component));

        if ($this->item_result->hasHints()) {
            $hint_component = new HintComponent($this->item_result->getHints());

            $tpl->setCurrentBlock('hints');
            $tpl->setVariable('HINTS', $DIC->ui()->renderer()->render($hint_component));
            $tpl->parseCurrentBlock();
        }

        $tpl->setVariable('BUTTONS', $this->createButtons());
        $DIC->ui()->mainTemplate()->setContent($tpl->get());
    }

    private function previousQuestion() : void
    {
        $this->redirectToQuestion($this->test_service->getPreviousQuestionId($this->result_id, $this->question->getId()));
    }

    private function nextQuestion() : void
    {
        $this->redirectToQuestion($this->test_service->getNextQuestionId($this->result_id, $this->question->getId()));
    }

    private function redirectToQuestion(Uuid $question_id) : void
    {
        global $DIC;

        $DIC->ctrl()->setParameter($this, self::PARAM_CURRENT_QUESTION, $question_id->toString());
        $DIC->ctrl()->redirectToURL($DIC->ctrl()->getLinkTarget($this, self::CMD_RUN_TEST, "", false, false));
    }

    private function getHint() : void
    {
        $this->item_result = $this->test_service->getItemResult($this->result_id, $this->question->getId());

        foreach ($this->question->getQuestionHints()->getHints() as $question_hint) {
            if (!in_array($question_hint, $this->item_result->getHints()->getHints())) {
                $this->test_service->hintRecieved($this->result_id, $this->question->getId(), $question_hint);
            }
        }

        $this->runTest();
    }

    private function showResults() : void
    {
        global $DIC, $ASQDIC;

        $html = '';
        $question_id = $this->test_service->getFirstQuestionId($this->result_id);

        do {
            $question = $ASQDIC->asq()->question()->getQuestionByQuestionId($question_id);
            $result = $this->test_service->getItemResult($this->result_id, $question_id);

            $hint_value = array_reduce($result->getHints()->getHints(), function ($sum, $hint) {
                return $sum += $hint->getPointDeduction();
            }, 0);

            try {
                $html .= sprintf(
                    '<div>Question: %s Score: %s Max Score: %s</div>',
                    $question_id->toString(),
                    $ASQDIC->asq()->answer()->getScore($question, $result->getAnswer()) - $hint_value,
                    $ASQDIC->asq()->answer()->getMaxScore($question)
                );
            } catch (Exception $e) {

            }
            $question_id = $this->test_service->getNextQuestionId($this->result_id, $question_id);
        } while (!is_null($question_id));

        $DIC->ui()->mainTemplate()->setContent($html);
    }

    private function storeAnswer() : void
    {
        global $ASQDIC;

        $this->loadQuestion();
        $component = $ASQDIC->asq()->ui()->getQuestionComponent($this->question)->withAnswerFromPost();
        $answer = $component->getAnswer();
        $this->test_service->addAnswer($this->result_id, $this->question->getId(), $answer);
    }

    private function loadQuestion() : void
    {
        global $DIC, $ASQDIC;

        $query_question_id = $_GET[self::PARAM_CURRENT_QUESTION];

        if (is_null($query_question_id) || empty($query_question_id)) {
            $question_id = $this->test_service->getFirstQuestionId($this->result_id);
        } else {
            $factory = new Factory();
            $question_id = $factory->fromString($query_question_id);
        }

        $DIC->ctrl()->setParameter($this, self::PARAM_CURRENT_QUESTION, $question_id->toString());
        $this->question = $ASQDIC->asq()->question()->getQuestionByQuestionId($question_id);
    }

    private function createButtons() : string
    {
        global $DIC;

        $buttons = [];

        if ($this->areHintsAvailable()) {
            $get_hint = ilSubmitButton::getInstance();
            $get_hint->setCaption($DIC->language()->txt('asqt_test_get_hint'), false);
            $get_hint->setCommand(self::CMD_GET_HINT);
            $buttons[] = $get_hint;
        }

        $previous_question = $this->test_service->getPreviousQuestionId($this->result_id, $this->question->getId());
        if (!is_null($previous_question)) {
            $prev_button = ilSubmitButton::getInstance();
            $prev_button->setCaption($DIC->language()->txt('asqt_test_prev_question'), false);
            $prev_button->setCommand(self::CMD_PREVIOUS_QUESTION);
            $buttons[] = $prev_button;
        }

        $save_button = ilSubmitButton::getInstance();
        $save_button->setCaption($DIC->language()->txt('asqt_test_save_answer'), false);
        $save_button->setCommand(self::CMD_RUN_TEST);
        $buttons[] = $save_button;

        $next_question = $this->test_service->getNextQuestionId($this->result_id, $this->question->getId());
        if (!is_null($next_question)) {
            $next_button = ilSubmitButton::getInstance();
            $next_button->setCaption($DIC->language()->txt('asqt_test_next_question'), false);
            $next_button->setCommand(self::CMD_NEXT_QUESTION);
            $buttons[] = $next_button;
        }

        $show_results = ilSubmitButton::getInstance();
        $show_results->setCaption($DIC->language()->txt('asqt_test_show_results'), false);
        $show_results->setCommand(self::CMD_SHOW_RESULTS);
        $buttons[] = $show_results;

        return array_reduce($buttons, function (string $carry, ilSubmitButton $button) {
            return $carry . "&nbsp;" . $button->render();
        }, '');
    }

    private function areHintsAvailable() : bool
    {
        return $this->question->hasHints()
            && count($this->question->getQuestionHints()->getHints()) > count($this->item_result->getHints()->getHints());
    }
}
