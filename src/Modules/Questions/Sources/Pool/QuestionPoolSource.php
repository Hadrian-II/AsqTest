<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
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
        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Select Question Pool',
            'SELECTA'
        )));
    }

    protected function qpsPoolSelection() : void {
        $selection = new QuestionPoolSelection();

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Select Question Pool',
            $selection->render()
        )));
    }
}