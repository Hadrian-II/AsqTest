<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use ilCtrl;
use ILIAS\DI\HTTPServices;
use srag\asq\QuestionPool\Application\QuestionPoolService;
use srag\asq\Test\Domain\Section\Model\AssessmentSectionData;
use srag\asq\Test\Domain\Test\Event\TestSectionAddedEvent;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Lib\Event\IEventQueue;
use srag\asq\Test\Lib\Event\Standard\AddSectionEvent;
use srag\asq\Test\Lib\Event\Standard\ExecuteCommandEvent;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\Modules\Questions\QuestionPage;
use srag\asq\Test\UI\System\SetUIEvent;
use srag\asq\Test\UI\System\UIData;

/**
 * Class QuestionPoolSource
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs ag - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSource extends AbstractTestModule implements IQuestionSourceModule
{
    const PARAM_SELECTED_POOL = 'qpsSelectedPool';

    const SHOW_POOL_SELECTION = 'qpsPoolSelection';
    const CREATE_POOL_SOURCE = 'qpsCreate';

    private ilCtrl $ctrl;

    private HTTPServices $http;

    private QuestionPoolService $pool_service;

    public function __construct(IEventQueue $event_queue)
    {
        $this->pool_service = new QuestionPoolService();

        global $DIC;
        $this->ctrl = $DIC->ctrl();
        $this->http = $DIC->http();

        parent::__construct($event_queue);
    }

    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): string
    {
        return ITestModule::TYPE_QUESTION_SOURCE;
    }

    /**
     * @return array
     */
    public function getQuestions(): array
    {

    }

    public function getCommands(): array
    {
        return [
            self::SHOW_POOL_SELECTION,
            self::CREATE_POOL_SOURCE
        ];
    }

    public function getInitializationCommand(): string
    {
        return self::SHOW_POOL_SELECTION;
    }

    protected function qpsCreate() : void {
        $uuid = $this->http->request()->getQueryParams()[self::PARAM_SELECTED_POOL];

        $section_data = new AssessmentSectionData('Questions', true);
        $section_data->addClass(self::class, new QuestionPoolSourceConfiguration($uuid));

        $this->raiseEvent(new AddSectionEvent(
            $this,
            $section_data
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::SHOW_QUESTIONS
        ));
    }

    protected function qpsPoolSelection() : void {
        $selection = new QuestionPoolSelection();

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Select Question Pool',
            $selection->render()
        )));
    }
}