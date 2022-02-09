<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use Fluxlabs\Assessment\Tools\Domain\Modules\Access\AccessConfiguration;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\CommandDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\IModuleDefinition;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreObjectEvent;
use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\AbstractQuestionSelection;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class RandomQuestionSelection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomQuestionSelection extends AbstractQuestionSelection
{
    const CMD_INITIALIZE = 'initRandomQuestions';
    const CMD_SAVE_POINTS = 'saveRandomPoints';

    const PARAM_POINTS = 'points';
    /**
     * @param ?RandomQuestionSelectionConfiguration $config
     * @return RandomQuestionSelectionObject
     */
    public function createObject(AbstractValueObject $config = null) : RandomQuestionSelectionObject
    {
        return new RandomQuestionSelectionObject(
            $this->access->getObject($config->getSourceKey()),
            $config->getPoints());
    }

    public function initRandomQuestions() : void
    {
        $source_object = $this->readSource();

        $selection = new RandomQuestionSelectionObject($source_object);

        $this->storeAndReturn($source_object, $selection);
    }

    public function saveRandomPoints() : void
    {
        $selection_key = $this->getLinkParameter(self::PARAM_SOURCE_KEY);

        /** @var RandomQuestionSelectionObject $selection */
        $selection = $this->access->getObject($selection_key);

        $selection->storePoints();

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $selection
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::CMD_SHOW_QUESTIONS
        ));
    }

    public function getInitializationCommand(): string
    {
        return self::CMD_INITIALIZE;
    }

    public function getModuleDefinition(): IModuleDefinition
    {
        return new ModuleDefinition(
            ModuleDefinition::NO_CONFIG,
            [
                new CommandDefinition(
                    self::CMD_INITIALIZE,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                ),
                new CommandDefinition(
                    self::CMD_SAVE_POINTS,
                    AccessConfiguration::ACCESS_ADMIN,
                    QuestionPage::QUESTION_TAB
                )
            ],
            [
                QuestionPage::class
            ]
        );
    }

    public function getTitleKey(): string
    {
        return 'asqt_random_selection';
    }
}