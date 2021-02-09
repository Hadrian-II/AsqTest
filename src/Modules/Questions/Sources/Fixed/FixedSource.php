<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Fixed;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionModule;

/**
 * Class FixedSource
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class FixedSource extends AbstractTestModule implements IQuestionModule
{
    /**
     * {@inheritDoc}
     * @see ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_QUESTION_SOURCE;
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Modules\IQuestionModule::getQuestions()
     */
    public function getQuestions(): array
    {

    }
}