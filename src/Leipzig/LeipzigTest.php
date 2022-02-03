<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Leipzig;

use Fluxlabs\Assessment\Test\Application\Test\Event\StoreTestDataEvent;
use Fluxlabs\Assessment\Test\Application\Test\TestService;
use Fluxlabs\Assessment\Test\Infrastructure\Setup\lang\SetupAsqTestLanguages;
use Fluxlabs\Assessment\Test\Modules\Player\Page\PlayerPage;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random\RandomQuestionSelection;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\TaxonomyPool\TaxonomyQuestionPoolSource;
use Fluxlabs\Assessment\Test\Modules\Result\ResultPage;
use Fluxlabs\Assessment\Test\Modules\Scoring\Manual\CorrectionPage;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\RunManager;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\Domain\AbstractAsqPlugin;
use Fluxlabs\Assessment\Tools\Domain\ILIASReference;
use Fluxlabs\Assessment\Test\Domain\Test\Model\TestData;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSelectionModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSourceModule;
use Fluxlabs\Assessment\Test\Domain\Test\Persistence\TestType;
use Fluxlabs\Assessment\Test\Modules\Availability\Basic\BasicAvailability;
use Fluxlabs\Assessment\Test\Modules\Availability\Timed\TimedAvailability;
use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\All\SelectAllQuestions;
use Fluxlabs\Assessment\Test\Modules\Questions\Selection\Manual\ManualQuestionSelection;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\Fixed\FixedSource;
use Fluxlabs\Assessment\Test\Modules\Scoring\Automatic\AutomaticScoring;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\AssessmentTestStorage;
use Fluxlabs\Assessment\Tools\Domain\Modules\IliasOnline\IliasOnlineModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\Settings\SettingsPage;
use phpseclib\Crypt\Random;
use Whoops\Run;

/**
 * Class LeipzigTest
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class LeipzigTest extends AbstractAsqPlugin
{
    use LanguageTrait;

    private function __construct(ILIASReference $reference)
    {
        parent::__construct($reference);

        $this->loadLanguageModule('asq');
        $this->loadLanguageModule(SetupAsqTestLanguages::ASQ_TEST_LANGUAGE_PREFIX);

        $this->addModule(SelectAllQuestions::class);
        $this->addModule(RandomQuestionSelection::class);
        $this->addModule(TaxonomyQuestionPoolSource::class);
        $this->addModule(AssessmentTestStorage::class);
        $this->addModule(RunManager::class);
        $this->addModule(IliasOnlineModule::class);

        $this->addModule(PlayerPage::class);
        $this->addModule(QuestionPage::class);
        $this->addModule(SettingsPage::class);
        $this->addModule(CorrectionPage::class);
        $this->addModule(ResultPage::class);
    }

    public static function load(ILIASReference $reference) : LeipzigTest
    {
        return new LeipzigTest($reference);
    }

    public static function create(ILIASReference $reference, string $title, string $description) : LeipzigTest
    {
        $service = new TestService();
        $service->createTest($reference->getId());

        $test = new LeipzigTest($reference);

        $test->event_queue->raiseEvent(new StoreTestDataEvent($test, new TestData($title, $description)));

        return $test;
    }

    public function getTestType() : TestType
    {
        return TestType::createType('aqtl', 'Test für Leipzig', self::class);
    }

    public static function getInitialCommand(): string
    {
        return QuestionPage::CMD_SHOW_QUESTIONS;
    }
}