<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Fixed;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;

/**
 * Class FixedSource
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class FixedSource extends AbstractTestModule implements IQuestionSourceModule
{
    const CREATE_STATIC_SOURCE = 'sqsCreate';

    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): string
    {
        return ITestModule::TYPE_QUESTION_SOURCE;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule::getQuestions()
     */
    public function getQuestions(): array
    {

    }

    public function getCommands() : array
    {
        return [
            self::CREATE_STATIC_SOURCE
        ];
    }

    public function getInitializationCommand(): string
    {
        return self::CREATE_STATIC_SOURCE;
    }

    protected function sqsCreate() : string
    {
        return 'FIXED SOURCE';
    }
}