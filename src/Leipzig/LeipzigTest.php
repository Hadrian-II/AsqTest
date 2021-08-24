<?php
declare(strict_types = 1);

namespace srag\asq\Test\Leipzig;

use srag\asq\Test\Domain\Test\AbstractTest;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Persistence\TestType;
use srag\asq\Test\Modules\Availability\Basic\BasicAvailability;
use srag\asq\Test\Modules\Availability\Timed\TimedAvailability;
use srag\asq\Test\Modules\Player\QuestionDisplay\QuestionDisplay;
use srag\asq\Test\Modules\Player\TextualInOut\TextualInOut;
use srag\asq\Test\Modules\Questions\Page\QuestionPage;
use srag\asq\Test\Modules\Questions\Selection\All\SelectAllQuestions;
use srag\asq\Test\Modules\Questions\Selection\Manual\ManualQuestionSelection;
use srag\asq\Test\Modules\Questions\Selection\Random\RandomQuestionSelection;
use srag\asq\Test\Modules\Questions\Sources\Fixed\FixedSource;
use srag\asq\Test\Modules\Questions\Sources\Pool\QuestionPoolSource;
use srag\asq\Test\Modules\Scoring\Automatic\AutomaticScoring;

/**
 * Class LeipzigTest
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class LeipzigTest extends AbstractTest
{
    public function __construct(AssessmentTestDto $test_data)
    {
        parent::__construct($test_data);

        $this->addModule(new BasicAvailability($this->event_queue, $this->access));
        $this->addModule(new TimedAvailability($this->event_queue, $this->access));
        $this->addModule(new QuestionDisplay($this->event_queue, $this->access));
        $this->addModule(new TextualInOut($this->event_queue, $this->access));
        $this->addModule(new SelectAllQuestions($this->event_queue, $this->access));
        $this->addModule(new ManualQuestionSelection($this->event_queue, $this->access));
        $this->addModule(new FixedSource($this->event_queue, $this->access));
        $this->addModule(new QuestionPoolSource($this->event_queue, $this->access));
        $this->addModule(new AutomaticScoring($this->event_queue, $this->access));

        $this->addModule(new QuestionPage(
            $this->event_queue,
            $this->access,
            $this->getModulesOfType(IQuestionSourceModule::class),
            $this->getModulesOfType(IQuestionSelectionModule::class)
        ));
    }

    public function getTestType() : TestType
    {
        return TestType::createType('aqtl', 'Test für Leipzig', self::class);
    }

    public static function getInitialCommand(): string
    {
        return QuestionPage::SHOW_QUESTIONS;
    }
}