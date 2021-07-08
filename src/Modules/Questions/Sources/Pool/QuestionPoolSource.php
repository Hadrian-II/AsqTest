<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;

/**
 * Class QuestionPoolSource
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionPoolSource extends AbstractTestModule implements IQuestionSourceModule
{
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

    protected function qpsCreate() : string {

    }

    protected function qpsPoolSelection() : string {
        return 'POOL SOURCE';
    }
}