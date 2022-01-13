<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool;

use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\Domain\Modules\Access\AccessConfiguration;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\CommandDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\IModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Objects\IAsqObject;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreObjectEvent;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ILIAS\Data\UUID\Factory;
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
    use CtrlTrait;

    const PARAM_SELECTED_POOL = 'qpsSelectedPool';

    const SHOW_POOL_SELECTION = 'qpsPoolSelection';
    const CREATE_POOL_SOURCE = 'qpsCreate';

    public function getInitializationCommand(): string
    {
        return self::SHOW_POOL_SELECTION;
    }

    protected function qpsCreate() : void {
        $factory = new Factory();
        $uuid = $factory->fromString($this->getLinkParameter(self::PARAM_SELECTED_POOL));

        $pool_source = new QuestionPoolSourceObject($uuid);

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $pool_source
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::CMD_SHOW_QUESTIONS
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

    public function getModuleDefinition(): IModuleDefinition
    {
        return new ModuleDefinition(
            ModuleDefinition::NO_CONFIG,
            [
                new CommandDefinition(
                    self::SHOW_POOL_SELECTION,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                ),
                new CommandDefinition(
                    self::CREATE_POOL_SOURCE,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                )
            ],
            [
                QuestionPage::class
            ]
        );
    }
}