<?php
declare(strict_types = 1);

namespace srag\asq\Test\Leipzig;

use srag\asq\Test\Domain\Test\AbstractTest;
use srag\asq\Test\Domain\Test\Modules\ITestModule;
use srag\asq\Test\Domain\Test\Persistence\TestType;
use srag\asq\Test\Modules\Availability\Basic\BasicAvailability;
use srag\asq\Test\Modules\Availability\Timed\TimedAvailability;
use srag\asq\Test\Modules\Player\QuestionDisplay\QuestionDisplay;
use srag\asq\Test\Modules\Player\TextualInOut\TextualInOut;
use srag\asq\Test\Modules\Questions\Selection\QuestionSelection;
use srag\asq\Test\Modules\Questions\Sources\Fixed\FixedSource;
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
    public function __construct()
    {
        $this->addModule(new BasicAvailability());
        $this->addModule(new TimedAvailability());
        $this->addModule(new QuestionDisplay());
        $this->addModule(new TextualInOut());
        $this->addModule(new QuestionSelection());
        $this->addModule(new FixedSource());
        $this->addModule(new AutomaticScoring());
    }

    /**
     * @return TestType
     */
    public function getTestType() : TestType
    {
        return TestType::createType('aqtl', 'Test für Leipzig', self::class);
    }
}