<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Leipzig;

use Fluxlabs\Assessment\Test\Application\Test\Event\StoreTestDataEvent;
use Fluxlabs\Assessment\Test\Application\Test\TestService;
use Fluxlabs\Assessment\Tools\Domain\AbstractAsqPlugin;
use ILIAS\Data\UUID\Uuid;
use Fluxlabs\Assessment\Test\Domain\Test\Model\TestData;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSelectionModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSourceModule;
use Fluxlabs\Assessment\Test\Domain\Test\Persistence\TestType;
use Fluxlabs\Assessment\Test\Modules\Availability\Basic\BasicAvailability;
use Fluxlabs\Assessment\Test\Modules\Availability\Timed\TimedAvailability;
use Fluxlabs\Assessment\Test\Modules\Player\QuestionDisplay\QuestionDisplay;
use Fluxlabs\Assessment\Test\Modules\Player\TextualInOut\TextualInOut;
use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\All\SelectAllQuestions;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\Manual\ManualQuestionSelection;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\Fixed\FixedSource;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool\QuestionPoolSource;
use Fluxlabs\Assessment\Test\Modules\Scoring\Automatic\AutomaticScoring;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\AssessmentTestStorage;
use PHPUnit\Util\Test;

/**
 * Class LeipzigTest
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class LeipzigTest extends AbstractAsqPlugin
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
        $service = new TestService();
        $service->createTest($test_id);

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