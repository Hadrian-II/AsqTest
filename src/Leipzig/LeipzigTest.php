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
    /**
     * @var ITestModule[]
     */
    private $modules;

    public function __construct()
    {
        $this->modules = [
            new BasicAvailability(),
            new TimedAvailability(),
            new QuestionDisplay(),
            new TextualInOut(),
            new QuestionSelection(),
            new FixedSource(),
            new AutomaticScoring()
        ];
    }

    /**
     * @return TestType
     */
    public function getTestType() : TestType
    {
        return new TestType('aqtl', 'Test für Leipzig', self::class);
    }

    /**
     * @return ITestModule[]
     */
    public function getModules() : array
    {
        return $this->modules;
    }
}