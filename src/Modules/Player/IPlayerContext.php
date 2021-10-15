<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;
use srag\asq\Domain\QuestionDto;

/**
 * Class IPlayerContext
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IPlayerContext
{
    /**
     * Checks if currently there is a next question available
     *
     * @return bool
     */
    public function hasNextQuestion() : bool;

    /**
     * Checks if currently there is a previous question available
     *
     * @return bool
     */
    public function hasPreviousQuestion() : bool;

    /**
     * Gets the next question
     *
     * @return Uuid
     */
    public function getNextQuestion() : ?Uuid;

    /**
     * Gets the previous question
     *
     * @return Uuid
     */
    public function getPreviousQuestion() : ?Uuid;

    /**
     * Gets Current question data
     *
     * @return QuestionDto
     */
    public function getCurrentQuestion() : QuestionDto;

    /**
     * Gets given answer to question
     *
     * @return AbstractValueObject
     */
    public function getAnswer() : ?AbstractValueObject;

    /**
     * Gives answer to question
     *
     * @param AbstractValueObject $answer
     */
    public function setAnswer(AbstractValueObject $answer) : void;

    /**
     * Submits the test run
     */
    public function submitTest() : void;
}