<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

use srag\asq\Domain\QuestionDto;

/**
 * Interface IQuestionModule
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
interface IPlayerModule
{
    /**
     * @return QuestionDto
     */
    public function getFirstQuestion() : QuestionDto;

    /**
     * @return ?QuestionDto
     */
    public function getPreviousQuestion() : ?QuestionDto;

    /**
     * @return ?QuestionDto
     */
    public function getNextQuestion() : ?QuestionDto;
}