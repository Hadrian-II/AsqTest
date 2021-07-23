<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;

/**
 * Class QuestionSelection
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionSelection extends AbstractTestModule implements IQuestionSelectionModule
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
     * @see \srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule::getQuestions()
     */
    public function getQuestions(): array
    {

    }

    public function getInitializationCommand(): string
    {
        // TODO: Implement getInitializationCommand() method.
    }
}