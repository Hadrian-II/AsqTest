<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject;

use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Modules\Player\IPlayerContext;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;
use PHPUnit\Util\Test;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Application\Service\QuestionService;
use srag\asq\Domain\QuestionDto;

/**
 * Class AssessmentTestStorage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestContext implements IPlayerContext
{
    private TestRunnerService $service;

    private Uuid $result_id;

    private Uuid $current_question_id;
    private ?Uuid $next_question;
    private ?Uuid $previous_question;

    private QuestionDto $current_question;
    private ?AbstractValueObject $current_answer;

    private QuestionService $question_service;

    public function __construct(Uuid $result_id, ?Uuid $current_question_id, TestRunnerService $service)
    {
        global $ASQDIC;

        $this->result_id = $result_id;
        $this->service = $service;
        $this->question_service = $ASQDIC->asq()->question();

        $this->current_question_id = $current_question_id;
        if ($this->current_question_id === null) {
            $this->current_question_id = $this->service->getFirstQuestionId($this->result_id);
        }

        $this->next_question = $this->service->getNextQuestionId($this->result_id, $this->current_question_id);
        $this->previous_question = $this->service->getPreviousQuestionId($this->result_id, $this->current_question_id);
        $this->current_question = $this->question_service->getQuestionByQuestionId($this->current_question_id);
        $this->current_answer = $this->service->getItemResult($this->result_id, $this->current_question_id)->getAnswer();
    }

    public function hasNextQuestion(): bool
    {
        return $this->next_question !== null;
    }

    public function hasPreviousQuestion(): bool
    {
        return $this->previous_question !== null;
    }

    public function getNextQuestion(): ?Uuid
    {
        return $this->next_question;
    }

    public function getPreviousQuestion(): ?Uuid
    {
        return $this->previous_question;
    }

    public function getCurrentQuestion(): QuestionDto
    {
        return $this->current_question;
    }

    public function getAnswer(): ?AbstractValueObject
    {
        return $this->current_answer;
    }

    public function setAnswer(AbstractValueObject $answer): void
    {
        $this->service->addAnswer($this->result_id, $this->current_question_id, $answer);
    }

    public function submitTest(): void
    {
        $this->service->submitTestRun($this->result_id);
    }
}