<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Result;

use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\RunState;
use Fluxlabs\Assessment\Test\Modules\Scoring\Manual\CorrectionPageModuleDefinition;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\RunManager;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\DIC\UserTrait;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Domain\Modules\IModuleDefinition;
use Fluxlabs\Assessment\Tools\Domain\Modules\IPageModule;
use Fluxlabs\Assessment\Tools\Event\Standard\SetUIEvent;
use Fluxlabs\Assessment\Tools\UI\Components\AsqTable;
use Fluxlabs\Assessment\Tools\UI\System\UIData;
use ilTemplate;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class ResultPage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ResultPage extends AbstractAsqModule implements IPageModule
{
    use LanguageTrait;
    use PathHelper;
    use UserTrait;

    const CMD_SHOW_RESULTS = 'showResults';

    const RESULT_TAB = 'result_tab';

    const COL_RUN = 'run';
    const COL_USER = 'user';
    const COL_POINTS = 'points';
    const COL_MAX_POINTS = 'max_points';

    private RunManager $manager;

    protected function initialize() : void
    {
        $this->manager = $this->access->getModule(RunManager::class);
    }

    public function showResults() : void
    {
        $this->raiseEvent(new SetUIEvent($this, new UIData(
            $this->access->getStorage()->getTestData()->getTitle(),
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
                self::COL_USER => $this->getUsername($run->getUserId()),
                self::COL_POINTS => $run->getPoints(),
                self::COL_MAX_POINTS => $run->getMaxPoints()
            ];
        }, $this->manager->getEndresults());

        $table = new AsqTable($headers, $data);

        return $table->render();
    }

    public function getModuleDefinition(): IModuleDefinition
    {
        return new ResultPageModuleDefinition();
    }
}