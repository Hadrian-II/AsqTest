<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreObjectEvent;
use srag\asq\Domain\QuestionDto;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
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

        $this->storeAndReturn($selection);
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

    public function getCommands(): array
    {
        return [
            self::CMD_INITIALIZE,
            self::CMD_SAVE_POINTS
        ];
    }

    public function getInitializationCommand(): string
    {
        return self::CMD_INITIALIZE;
    }
}