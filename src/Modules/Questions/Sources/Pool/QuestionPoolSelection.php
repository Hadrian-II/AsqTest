<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use srag\asq\QuestionPool\Application\QuestionPoolService;

/**
 * Class QuestionPoolSelection
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs ag - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSelection
{
    private QuestionPoolService $pool_service;

    public function __construct() {
        $this->pool_service = new QuestionPoolService();
    }

    public function render() : string
    {
        $pools = $this->pool_service->getPools();

        $html = 'Question Selection:';

        foreach ($pools as $pool) {
            $html .= '<br>' . $pool->getTitle();
        }

        return $html;
    }
}