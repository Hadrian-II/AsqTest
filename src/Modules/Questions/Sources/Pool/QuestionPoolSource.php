<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use ILIAS\Data\UUID\Factory;
use ILIAS\DI\HTTPServices;
use srag\asq\Test\Domain\Test\ITestAccess;
use srag\asq\Test\Domain\Test\Objects\ITestObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\asq\Test\Lib\Event\IEventQueue;
use srag\asq\Test\Lib\Event\Standard\ForwardToCommandEvent;
use srag\asq\Test\Lib\Event\Standard\StoreObjectEvent;
use srag\asq\Test\Modules\Questions\Page\QuestionPage;
use srag\asq\Test\Modules\Questions\Sources\AbstractQuestionSource;
use srag\asq\Test\UI\System\SetUIEvent;
use srag\asq\Test\UI\System\UIData;

/**
 * Class QuestionPoolSource
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSource extends AbstractQuestionSource
{
    const PARAM_SELECTED_POOL = 'qpsSelectedPool';

    const SHOW_POOL_SELECTION = 'qpsPoolSelection';
    const CREATE_POOL_SOURCE = 'qpsCreate';

    private HTTPServices $http;

    public function __construct(IEventQueue $event_queue, ITestAccess $access)
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
     * @return ITestObject
     */
    public function createObject(ObjectConfiguration $config) : ITestObject
    {
        return new QuestionPoolSourceObject($config->getUuid());
    }
}