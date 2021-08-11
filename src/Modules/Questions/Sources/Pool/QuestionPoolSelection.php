<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use ilCtrl;
use ilTemplate;
use srag\asq\Infrastructure\Helpers\PathHelper;
use srag\asq\QuestionPool\Application\QuestionPoolService;

/**
 * Class QuestionPoolSelection
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSelection
{
    use PathHelper;

    const POOL_TITLE = 'POOL_TITLE';
    const POOL_DESCRIPTION = 'POOL_DESCRIPTION';
    const POOL_CREATOR = 'POOL_CREATOR';
    const POOL_SELECTION = "POOL_SELECTION";

    private QuestionPoolService $pool_service;

    private ilCtrl $ctrl;

    public function __construct() {
        $this->pool_service = new QuestionPoolService();

        global $DIC;
        $this->ctrl = $DIC->ctrl();
    }

    public function render() : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'src/Modules/Questions/Sources/Pool/poolSelectionTable.html', true, true);

        $tpl->setVariable('HEADER_POOL_TITLE','TODO_Titel');
        $tpl->setVariable('HEADER_POOL_DESCRIPTION', 'TODO_Description');
        $tpl->setVariable('HEADER_POOL_CREATOR', 'TODO_Ersteller');

        foreach ($this->pool_service->getPools() as $pool) {
            $tpl->setCurrentBlock('row');
            $tpl->setVariable('VAL_POOL_TITLE', $pool->getTitle());
            $tpl->setVariable('VAL_POOL_DESCRIPTION', $pool->getDescription());
            $tpl->setVariable('VAL_POOL_CREATOR', $pool->getCreatorId());
            $tpl->setVariable('VAL_POOL_SELECTION', $this->createSelectButton($pool->getUuid()));
            $tpl->parseCurrentBlock();
        }

        return $tpl->get();
    }


    private function createSelectButton(string $uuid) : string
    {
        $current_class = $this->ctrl->getCmdClass();

        $this->ctrl->setParameterByClass(
            $current_class,
            QuestionPoolSource::PARAM_SELECTED_POOL,
            $uuid);

        return sprintf('<a href="%s" class="btn btn-default">%s</a>',
                        $this->ctrl->getLinkTargetByClass(
                            $current_class,
                            QuestionPoolSource::CREATE_POOL_SOURCE
                        ),
                        'Auswählen');
    }
}