<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Module;

use Fluxlabs\Assessment\Tools\Domain\Modules\IAsqModule;
use srag\asq\Domain\QuestionDto;

/**
 * Interface IQuestionModule
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IPlayerModule extends IAsqModule
{
    public function getFirstQuestion() : QuestionDto;

    public function getPreviousQuestion() : ?QuestionDto;

    public function getNextQuestion() : ?QuestionDto;
}