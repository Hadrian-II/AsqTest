<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool;

use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Objects\IAsqObject;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreObjectEvent;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ILIAS\Data\UUID\Factory;
use ILIAS\HTTP\Services;
use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\AbstractQuestionSource;

/**
 * Class QuestionPoolSource
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSource extends AbstractQuestionSource
{
    const PARAM_SELECTED_POOL = 'qpsSelectedPool';

    const SHOW_POOL_SELECTION = 'qpsPoolSelection';
    const CREATE_POOL_SOURCE = 'qpsCreate';

    private Services $http;

    public function __construct(IEventQueue $event_queue, IObjectAccess $access)
    {
        global $DIC;
        $this->http = $DIC->http();

        parent::__construct($event_queue, $access);
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

    protected function qpsCreate() : void {
        $factory = new Factory();
        $uuid = $factory->fromString($this->http->request()->getQueryParams()[self::PARAM_SELECTED_POOL]);

        $pool_source = new QuestionPoolSourceObject($uuid);

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $pool_source
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::SHOW_QUESTIONS
        ));
    }

    protected function qpsPoolSelection() : void {
        $selection = new QuestionPoolSelection();

        $this->raiseEvent(new SetUIEvent($this, new UIData(
            'Select Question Pool',
            $selection->render()
        )));
    }

    /**
     * @param QuestionPoolSourceConfiguration $config
     * @return IAsqObject
     */
    public function createObject(ObjectConfiguration $config) : IAsqObject
    {
        return new QuestionPoolSourceObject($config->getUuid());
    }
}