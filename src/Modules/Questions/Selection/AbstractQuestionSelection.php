<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection;

use ILIAS\HTTP\Services;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\asq\Test\Lib\Event\IEventQueue;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\Lib\Event\Standard\StoreObjectEvent;
use srag\asq\Test\Modules\Questions\Page\QuestionPage;

/**
 * Abstract Class AbstractQuestionSelection
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractQuestionSelection extends AbstractTestModule implements IQuestionSelectionModule
{
    protected Services $http;

    protected AsqServices $asq;

    public function __construct(IEventQueue $event_queue, ITestAccess $access)
    {
        global $DIC;
        $this->http = $DIC->http();
        global $ASQDIC;
        $this->asq = $ASQDIC->asq();

        parent::__construct($event_queue, $access);
    }

    protected function readSource() : ISourceObject
    {
        $source_key = $this->http->request()->getQueryParams()[IQuestionSelectionModule::PARAM_SOURCE_KEY];

        return $this->access->getObject($source_key);
    }

    protected function storeAndReturn(ISelectionObject $selection)
    {
        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $selection
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::SHOW_QUESTIONS
        ));
    }

    public function getType(): string
    {
        return ITestModule::TYPE_QUESTION_SELECTION;
    }

    public function getConfigClass() : ?string
    {
        return null;
    }

    public function getQuestionPageActions(ISelectionObject $object): string
    {
        //no actions
        return '';
    }
}