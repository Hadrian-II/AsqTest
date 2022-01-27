<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\All;

use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;
use Fluxlabs\Assessment\Tools\Domain\Modules\Access\AccessConfiguration;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\CommandDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\Definition\ModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\IModuleDefinition;
use srag\asq\Domain\QuestionDto;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\AbstractQuestionSelection;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class SelectAllQuestions
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SelectAllQuestions extends AbstractQuestionSelection
{
    const CMD_INITIALIZE = 'initAllQuestions';

    /**
     * @param ?SelectAllQuestionsConfiguration $config
     * @return SelectAllQuestionsObject
     */
    public function createObject(AbstractValueObject $config = null) : SelectAllQuestionsObject
    {
        return new SelectAllQuestionsObject($this->access->getObject($config->getSourceKey()));
    }

    public function initAllQuestions() : void
    {
        $source_object = $this->readSource();

        $selection = new SelectAllQuestionsObject($source_object);

        $this->storeAndReturn($selection);
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
                )
            ],
            [
                QuestionPage::class
            ]
        );
    }

    public function getTitleKey(): string
    {
        return 'asqt_select_all';
    }
}