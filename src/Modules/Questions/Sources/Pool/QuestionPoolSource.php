<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use srag\asq\Test\Domain\Test\Model\AbstractTestModule;
use srag\asq\Test\Domain\Test\Model\ITestModule;
/**
 * Class QuestionPoolSource
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionPoolSource extends AbstractTestModule
{
    /**
     * {@inheritDoc}
     * @see \srag\asq\Test\Domain\Test\Model\ITestModule::getType()
     */
    public function getType(): int
    {
        return ITestModule::TYPE_QUESTION_SOURCE;
    }
}