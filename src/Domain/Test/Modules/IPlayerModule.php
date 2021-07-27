<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Domain\QuestionDto;

/**
 * Interface IQuestionModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IPlayerModule
{
    public function getFirstQuestion() : QuestionDto;

    public function getPreviousQuestion() : ?QuestionDto;

    public function getNextQuestion() : ?QuestionDto;
}