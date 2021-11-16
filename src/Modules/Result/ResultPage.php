<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Result;

use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\InstanceState;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\RunState;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\RunManager;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use Fluxlabs\Assessment\Tools\Event\Standard\AddTabEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\UI\Components\AsqTable;
use Fluxlabs\Assessment\Tools\UI\System\TabDefinition;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ilTemplate;
use srag\asq\Application\Service\AnswerService;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class CorrectionPage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ResultPage extends AbstractAsqModule implements IPageModule
{
    use LanguageTrait;
    use PathHelper;

    const CMD_SHOW_RESULTS = 'showResults';

    const COL_RUN = 'run';
    const COL_USER = 'user';
    const COL_POINTS = 'points';
    const COL_MAX_POINTS = 'max_points';

    private RunManager $manager;

    public function __construct(IEventQueue $event_queue, IObjectAccess $access)
    {
        parent::__construct($event_queue, $access);

        $this->manager = $access->getModule(RunManager::class);

        $this->raiseEvent(new AddTabEvent(
            $this,
            new TabDefinition(self::class, $this->txt('asqt_results'), self::CMD_SHOW_RESULTS)
        ));
    }

    public function showResults() : void
    {
        $this->raiseEvent(new SetUIEvent($this, new UIData(
            $this->txt('asqt_results'),
            $this->renderContent()
        )));
    }


    private function renderContent() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Result/ResultPage.html', true, true);

        $tpl->setVariable('RESULTS', $this->renderResults());

        return $tpl->get();
    }

    private function renderResults() : string
    {
        $headers = [
            self::COL_RUN => $this->txt('asqt_id'),
            self::COL_USER => $this->txt('asqt_user'),
            self::COL_POINTS => $this->txt('asqt_points'),
            self::COL_MAX_POINTS => $this->txt('asqt_max_score')
        ];

        $data = array_map(function(RunState $run) {
            return [
                self::COL_RUN => $run->getAggregateId()->toString(),
                self::COL_USER => $run->getUserId(),
                self::COL_POINTS => $run->getPoints(),
                self::COL_MAX_POINTS => $run->getMaxPoints()
            ];
        }, $this->manager->getEndresults());

        $table = new AsqTable($headers, $data);

        return $table->render();
    }

    public function getCommands(): array
    {
        return [
            self::CMD_SHOW_RESULTS
        ];
    }
}