<?php

use srag\asq\AsqGateway;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Application\TestRunner\TestRunnerService;
use srag\asq\Test\Domain\Result\Model\ItemResult;
use srag\asq\UserInterface\Web\PathHelper;
use srag\asq\UserInterface\Web\Component\Hint\HintComponent;

/**
 * Class TestPlayerGUI
 *
 * @author studer + raimann ag - Adrian Lüthi <al@studer-raimann.ch>
 */
class TestPlayerGUI {
    const LANG_TEST = 'test';
    
    const CMD_PREVIOUS_QUESTION = 'previousQuestion';
    const CMD_NEXT_QUESTION = 'nextQuestion';
    const CMD_RUN_TEST = 'runTest';
    const CMD_SHOW_RESULTS = 'showResults';
    const CMD_GET_HINT = 'getHint';
    const PARAM_CURRENT_RESULT = 'currentResult';
    const PARAM_CURRENT_QUESTION = 'currentQuestion';
    
    /**
     * @var string
     */
    private $result_id;
    
    /**
     * @var QuestionDto
     */
    private $question;
    
    /**
     * @var TestRunnerService
     */
    private $test_service;
    
    /**
     * @var ItemResult
     */
    private $item_result;
    
    /**
     * @throws AsqException
     */
    public function __construct() {
        global $DIC;
        
        $this->test_service = new TestRunnerService();
        $DIC->language()->loadLanguageModule('asqt');
        
        $this->result_id = $_GET[self::PARAM_CURRENT_RESULT];
        $DIC->ctrl()->setParameter($this, self::PARAM_CURRENT_RESULT, $this->result_id);
        
        if (is_null($this->result_id) || empty($this->result_id)) {
            throw new AsqException('AssessmentResult id not set (PARAM_CURRENT_RESULT)');
        }
    }
    
    /**
     *
     * @param string $cmd
     */
    public function executeCommand()/*: void*/
    {
        global $DIC;
        
        $this->loadQuestion();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->storeAnswer();
        }
        
        $cmd = $DIC->ctrl()->getCmd();
        $this->{$cmd}();
    }
    
    private function runTest() {
        global $DIC;

        $component = AsqGateway::get()->ui()->getQuestionComponent($this->question);
        
        $this->item_result = $this->test_service->getItemResult($this->result_id, $this->question->getId());
        if (!is_null($this->item_result) && !is_null($this->item_result->getAnswer())) {
            $component->setAnswer($this->item_result->getAnswer());
        }
        
        $tpl = new ilTemplate(PathHelper::getBasePath(__DIR__) . 'templates/default/tpl.test_player.html', true, true);
        $tpl->setVariable('FORM_ACTION', $DIC->ctrl()->getFormAction($this, self::CMD_RUN_TEST));
        $tpl->setVariable('QUESTION_COMPONENT', $component->renderHtml());
        
        if ($this->item_result->hasHints()) {
            $hint_component = new HintComponent($this->item_result->getHints());
            
            $tpl->setCurrentBlock('hints');
            $tpl->setVariable('HINTS', $hint_component->getHtml());
            $tpl->parseCurrentBlock();
        }
        
        $tpl->setVariable('BUTTONS', $this->createButtons());
        $DIC->ui()->mainTemplate()->setContent($tpl->get());
    }
    
    private function previousQuestion() {
        $this->redirectToQuestion($this->test_service->getPreviousQuestionId($this->result_id, $this->question->getId()));
    }
    
    private function nextQuestion() {
        $this->redirectToQuestion($this->test_service->getNextQuestionId($this->result_id, $this->question->getId()));
    }
    
    private function redirectToQuestion(string $question_id) {
        global $DIC;
        
        $DIC->ctrl()->setParameter($this, self::PARAM_CURRENT_QUESTION, $question_id);
        $DIC->ctrl()->redirectToURL($DIC->ctrl()->getLinkTarget($this, self::CMD_RUN_TEST, "", false, false));
    }
    
    private function getHint() {
        $this->item_result = $this->test_service->getItemResult($this->result_id, $this->question->getId());
        
        foreach ($this->question->getQuestionHints()->getHints() as $question_hint) {
            if (!in_array($question_hint, $this->item_result->getHints()->getHints())) {
                $this->test_service->hintRecieved($this->result_id, $this->question->getId(), $question_hint);
            }
        }
        
        $this->runTest();
    }
    
    private function showResults() {
        global $DIC;
        
        $html = '';
        $question_id = $this->test_service->getFirstQuestionId($this->result_id);
        
        do {
            $question = AsqGateway::get()->question()->getQuestionByQuestionId($question_id);
            $result = $this->test_service->getItemResult($this->result_id, $question_id);
            $hint_value = array_reduce($result->getHints()->getHints(), function($sum, $hint) {
                return $sum += $hint->getPointDeduction();
            }, 0);
            
            $html .= sprintf(
                '<div>Question: %s Score: %s Max Score: %s</div>', 
                $question_id,
                AsqGateway::get()->answer()->getScore($question, $result->getAnswer()) - $hint_value,
                AsqGateway::get()->answer()->getMaxScore($question));
            $question_id = $this->test_service->getNextQuestionId($this->result_id, $question_id);
        } while (!is_null($question_id));
        
        $DIC->ui()->mainTemplate()->setContent($html);
    }
    
    private function storeAnswer() {
        $this->loadQuestion();
        $component = AsqGateway::get()->ui()->getQuestionComponent($this->question);
        $answer = $component->readAnswer();
        $this->test_service->addAnswer($this->result_id, $this->question->getId(), $answer);
    }
    
    private function loadQuestion() {
        global $DIC;
        
        $question_id = $_GET[self::PARAM_CURRENT_QUESTION];
            
        if (is_null($question_id) || empty($question_id)) {
            $question_id = $this->test_service->getFirstQuestionId($this->result_id);
        }
        
        $DIC->ctrl()->setParameter($this, self::PARAM_CURRENT_QUESTION, $question_id);
        $this->question = AsqGateway::get()->question()->getQuestionByQuestionId($question_id);
    }
    
    private function createButtons() : string {
        global $DIC;
        
        $buttons = [];
        
        if ($this->areHintsAvailable()) {
            $get_hint = ilSubmitButton::getInstance();
            $get_hint->setCaption($DIC->language()->txt('asqt_get_hint'), false);
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
        
        return array_reduce($buttons, function(string $carry, ilSubmitButton $button) {
            return $carry . "&nbsp;" . $button->render();
        }, '');
    }
    
    /**
     * @return bool
     */
    private function areHintsAvailable() : bool {
        return $this->question->hasHints() 
            && count($this->question->getQuestionHints()->getHints()) > count($this->item_result->getHints()->getHints());
    }
}