<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionModule;
/**
 * Class QuestionSelection
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionSelection extends AbstractTestModule implements IQuestionModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): string
    {
        return ITestModule::TYPE_QUESTION_SELECTION;
    }

    /**
     * {@inheritDoc}
     * @see ITestModule::getConfigClass()
     */
    public function getConfigClass() : ?string
    {
        return QuestionSelectionConfigurationFactory::class;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Modules\IQuestionModule::getQuestions()
     */
    public function getQuestions(): array
    {

    }
}