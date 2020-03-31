<?php

use srag\asq\AsqGateway;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\QuestionDto;
use srag\asq\Test\Application\Service\TestService;
use srag\asq\Test\Domain\Model\AssessmentResult;

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
    const PARAM_CURRENT_RESULT = 'currentResult';
    const PARAM_CURRENT_QUESTION = 'currentQuestion';
    
    /**
     * @var AssessmentResult
     */
    private $result_id;
    
    /**
     * @var QuestionDto
     */
    private $question;
    
    /**
     * @var TestService
     */
    private $test_service;
    
    public function __construct() {
        $this->test_service = new TestService();
        
        $this->result_id = $_GET[self::PARAM_CURRENT_RESULT];
        self::dic()->ctrl()->setParameter($this, self::PARAM_CURRENT_RESULT, $this->result_id);
        
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
        $cmd = self::dic()->ctrl()->getCmd();
        $this->{$cmd}();
    }
    
    private function runTest() {
        $this->loadQuestion();
        
        $component = AsqGateway::get()->ui()->getQuestionComponent($this->question);
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $answer = $this->test_service->getAnswer($this->result_id, $this->question->getId());
            if (!is_null($answer)) {
                $component->setAnswer($answer);
            }
        }
        else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $answer = $component->readAnswer();
            $this->test_service->addAnswer($this->result_id, $this->question->getId(), $answer);
        }
        
        self::dic()->ui()->mainTemplate()->setContent('<div style="background-color: white; border: 1px solid #D6D6D6; padding: 20px;"><form method="post" action="' . self::dic()->ctrl()->getFormAction($this, self::CMD_RUN_TEST) . '">' . $component->renderHtml() . '<br />' . $this->createButtons() . '</form></div>');
    }
    
    private function previousQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->storeAnswer();
            $this->redirectToQuestion($this->test_service->getPreviousQuestionId($this->result_id, $this->question->getId()));
        }
    }
    
    private function nextQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->storeAnswer();
            $this->redirectToQuestion($this->test_service->getNextQuestionId($this->result_id, $this->question->getId()));
        }
    }
    
    private function redirectToQuestion(string $question_id) {
        self::dic()->ctrl()->setParameter($this, self::PARAM_CURRENT_QUESTION, $question_id);
        self::dic()->ctrl()->redirectToURL(self::dic()->ctrl()->getLinkTarget($this, self::CMD_RUN_TEST, "", false, false));
    }
    
    private function showResults() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->storeAnswer();
        }
        
        $html = '';
        $question_id = $this->test_service->getFirstQuestionId($this->result_id);
        
        do {
            $question = AsqGateway::get()->question()->getQuestionByQuestionId($question_id);
            $answer = $this->test_service->getAnswer($this->result_id, $question_id);
            
            $html .= sprintf(
                '<div>Question: %s Score: %s Max Score: %s</div>', 
                $question_id,
                AsqGateway::get()->answer()->getScore($question, $answer),
                AsqGateway::get()->answer()->getMaxScore($question));
            $question_id = $this->test_service->getNextQuestionId($this->result_id, $question_id);
        } while (!is_null($question_id));
        
        self::dic()->ui()->mainTemplate()->setContent($html);
    }
    
    private function storeAnswer() {
        $this->loadQuestion();
        $component = AsqGateway::get()->ui()->getQuestionComponent($this->question);
        $answer = $component->readAnswer();
        $this->test_service->addAnswer($this->result_id, $this->question->getId(), $answer);
    }
    
    private function loadQuestion() {
        $question_id = $_GET[self::PARAM_CURRENT_QUESTION];
            
        if (is_null($question_id) || empty($question_id)) {
            $question_id = $this->test_service->getFirstQuestionId($this->result_id);
        }
        
        self::dic()->ctrl()->setParameter($this, self::PARAM_CURRENT_QUESTION, $question_id);
        $this->question = AsqGateway::get()->question()->getQuestionByQuestionId($question_id);
    }
    
    private function createButtons() : string {
        global $DIC;
        
        $buttons = [];
        
        $previous_question = $this->test_service->getPreviousQuestionId($this->result_id, $this->question->getId());
        if (!is_null($previous_question)) {
            $prev_button = ilSubmitButton::getInstance();
            $prev_button->setCaption($DIC->language()->txt('prev_question'), false);
            $prev_button->setCommand(self::CMD_PREVIOUS_QUESTION);
            $buttons[] = $prev_button;
        }
        
        $save_button = ilSubmitButton::getInstance();
        $save_button->setCaption($DIC->language()->txt('save_answer'), false);
        $save_button->setCommand(self::CMD_RUN_TEST);
        $buttons[] = $save_button;
        
        $next_question = $this->test_service->getNextQuestionId($this->result_id, $this->question->getId());
        if (!is_null($next_question)) {
            $next_button = ilSubmitButton::getInstance();
            $next_button->setCaption($DIC->language()->txt('next_question'), false);
            $next_button->setCommand(self::CMD_NEXT_QUESTION);
            $buttons[] = $next_button;
        }

        $show_results = ilSubmitButton::getInstance();
        $show_results->setCaption($DIC->language()->txt('show_results'), false);
        $show_results->setCommand(self::CMD_SHOW_RESULTS);
        $buttons[] = $show_results;
        
        return array_reduce($buttons, function(string $carry, ilSubmitButton $button) {
            return $carry . "&nbsp;" . $button->render();
        }, '');
    }
}