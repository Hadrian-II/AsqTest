<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources;

use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;

/**
 * Abstract Class AbstractQuestionSource
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractQuestionSource extends AbstractTestModule implements IQuestionSourceModule
{
    public function getType(): string
    {
        return ITestModule::TYPE_QUESTION_SOURCE;
    }

    public function getQuestionPageActions(ISourceObject $object): string
    {
        //no actions
        return '';
    }
}