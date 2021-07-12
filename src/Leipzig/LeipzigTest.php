<?php
declare(strict_types = 1);

namespace srag\asq\Test\Leipzig;

use srag\asq\Test\Domain\Test\AbstractTest;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\asq\Test\Domain\Test\Modules\AbstractTestModule;
use srag\asq\Test\Domain\Test\Modules\IQuestionSourceModule;
use srag\asq\Test\Domain\Test\Persistence\TestType;
use srag\asq\Test\Modules\Availability\Basic\BasicAvailability;
use srag\asq\Test\Modules\Availability\Timed\TimedAvailability;
use srag\asq\Test\Modules\Player\QuestionDisplay\QuestionDisplay;
use srag\asq\Test\Modules\Player\TextualInOut\TextualInOut;
use srag\asq\Test\Modules\Questions\QuestionPage;
use srag\asq\Test\Modules\Questions\Selection\QuestionSelection;
use srag\asq\Test\Modules\Questions\Sources\Fixed\FixedSource;
use srag\asq\Test\Modules\Questions\Sources\Pool\QuestionPoolSource;
use srag\asq\Test\Modules\Scoring\Automatic\AutomaticScoring;

/**
 * Class LeipzigTest
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class LeipzigTest extends AbstractTest
{
    public function __construct(AssessmentTestDto $test_data)
    {
        parent::__construct($test_data);

        $this->addModule(new BasicAvailability($this->event_queue));
        $this->addModule(new TimedAvailability($this->event_queue));
        $this->addModule(new QuestionDisplay($this->event_queue));
        $this->addModule(new TextualInOut($this->event_queue));
        $this->addModule(new QuestionSelection($this->event_queue));
        $this->addModule(new FixedSource($this->event_queue));
        $this->addModule(new QuestionPoolSource($this->event_queue));
        $this->addModule(new AutomaticScoring($this->event_queue));

        $this->addModule(new QuestionPage($this->event_queue, $test_data, $this->getModulesOfType(IQuestionSourceModule::class)));
    }

    /**
     * @return TestType
     */
    public function getTestType() : TestType
    {
        return TestType::createType('aqtl', 'Test für Leipzig', self::class);
    }
}