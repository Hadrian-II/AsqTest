<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection;

use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Lib\Event\IEventQueue;

/**
 * Abstract Class AbstractQuestionSelection
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractQuestionSelection extends AbstractTestModule implements IQuestionSelectionModule
{
    public function __construct(IEventQueue $event_queue, ITestAccess $access)
    {
        parent::__construct($event_queue, $access);
    }

    public function getType(): string
    {
        return ITestModule::TYPE_QUESTION_SELECTION;
    }

    public function getConfigClass() : ?string
    {
        return null;
    }
}