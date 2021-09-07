<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection;

use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreObjectEvent;
use ILIAS\HTTP\Services;
use srag\asq\Application\Service\AsqServices;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSelectionModule;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;
use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;

/**
 * Abstract Class AbstractQuestionSelection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
abstract class AbstractQuestionSelection extends AbstractAsqModule implements IQuestionSelectionModule
{
    protected Services $http;

    protected AsqServices $asq;

    public function __construct(IEventQueue $event_queue, IObjectAccess $access)
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