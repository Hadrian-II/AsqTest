<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool;

use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\DIC\UserTrait;
use ilTemplate;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\QuestionPool\Application\QuestionPoolService;

/**
 * Class QuestionPoolSelection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSelection
{
    use PathHelper;
    use CtrlTrait;
    use LanguageTrait;
    use UserTrait;

    const POOL_TITLE = 'POOL_TITLE';
    const POOL_DESCRIPTION = 'POOL_DESCRIPTION';
    const POOL_CREATOR = 'POOL_CREATOR';
    const POOL_SELECTION = "POOL_SELECTION";

    private QuestionPoolService $pool_service;

    public function __construct() {
        $this->pool_service = new QuestionPoolService();
    }

    public function render() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Questions/Sources/Pool/poolSelectionTable.html', true, true);

        $tpl->setVariable('HEADER_POOL_TITLE', $this->txt('asqt_title'));
        $tpl->setVariable('HEADER_POOL_DESCRIPTION', $this->txt('asqt_description'));
        $tpl->setVariable('HEADER_POOL_CREATOR', $this->txt('asqt_creator'));

        foreach ($this->pool_service->getPools() as $pool) {
            $tpl->setCurrentBlock('row');
            $tpl->setVariable('VAL_POOL_TITLE', $pool->getTitle());
            $tpl->setVariable('VAL_POOL_DESCRIPTION', $pool->getDescription());
            $tpl->setVariable('VAL_POOL_CREATOR', $this->getUsername($pool->getCreatorId()));
            $tpl->setVariable('VAL_POOL_SELECTION', $this->createSelectButton($pool->getUuid()));
            $tpl->parseCurrentBlock();
        }

        return $tpl->get();
    }


    private function createSelectButton(string $uuid) : string
    {
        $this->setLinkParameter(QuestionPoolSource::PARAM_SELECTED_POOL, $uuid);

        return sprintf('<a href="%s" class="btn btn-default">%s</a>',
                        $this->getCommandLink(QuestionPoolSource::CREATE_POOL_SOURCE),
                        $this->txt('asqt_select'));
    }
}