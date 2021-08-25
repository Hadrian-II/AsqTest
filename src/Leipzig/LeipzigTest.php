<?php
declare(strict_types = 1);

namespace srag\asq\Test\Leipzig;

use ILIAS\Data\UUID\Uuid;
use srag\asq\Test\Domain\Test\AbstractTest;
use srag\asq\Test\Domain\Test\Model\TestData;
use srag\asq\Test\Domain\Test\Modules\IQuestionSelectionModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Persistence\TestType;
use srag\asq\Test\Lib\Event\Standard\StoreTestDataEvent;
use srag\asq\Test\Modules\Availability\Basic\BasicAvailability;
use srag\asq\Test\Modules\Availability\Timed\TimedAvailability;
use srag\asq\Test\Modules\Player\QuestionDisplay\QuestionDisplay;
use srag\asq\Test\Modules\Player\TextualInOut\TextualInOut;
use srag\asq\Test\Modules\Questions\Page\QuestionPage;
use srag\asq\Test\Modules\Questions\Selection\All\SelectAllQuestions;
use srag\asq\Test\Modules\Questions\Selection\Manual\ManualQuestionSelection;
use srag\asq\Test\Modules\Questions\Sources\Fixed\FixedSource;
use srag\asq\Test\Modules\Questions\Sources\Pool\QuestionPoolSource;
use srag\asq\Test\Modules\Scoring\Automatic\AutomaticScoring;
use srag\asq\Test\Modules\Storage\AssessmentTestObject\AssessmentTestStorage;

/**
 * Class LeipzigTest
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class LeipzigTest extends AbstractTest
{
    private function __construct(Uuid $test_id)
    {
        parent::__construct();

        $this->addModule(new BasicAvailability($this->event_queue, $this->access));
        $this->addModule(new TimedAvailability($this->event_queue, $this->access));
        $this->addModule(new QuestionDisplay($this->event_queue, $this->access));
        $this->addModule(new TextualInOut($this->event_queue, $this->access));
        $this->addModule(new SelectAllQuestions($this->event_queue, $this->access));
        $this->addModule(new ManualQuestionSelection($this->event_queue, $this->access));
        $this->addModule(new FixedSource($this->event_queue, $this->access));
        $this->addModule(new QuestionPoolSource($this->event_queue, $this->access));
        $this->addModule(new AutomaticScoring($this->event_queue, $this->access));
        $this->addModule(new AssessmentTestStorage($this->event_queue, $this->access, $test_id));

        $this->addModule(new QuestionPage(
            $this->event_queue,
            $this->access,
            $this->getModulesOfType(IQuestionSourceModule::class),
            $this->getModulesOfType(IQuestionSelectionModule::class)
        ));
    }

    public static function load(Uuid $test_id) : LeipzigTest
    {
        return new LeipzigTest($test_id);
    }

    public static function create(Uuid $test_id, string $title, string $description) : LeipzigTest
    {
        $test = new LeipzigTest($test_id);

        $test->event_queue->raiseEvent(new StoreTestDataEvent($test, new TestData($title, $description)));

        return $test;
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