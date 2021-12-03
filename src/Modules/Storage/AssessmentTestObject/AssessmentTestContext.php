<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject;

use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Modules\Player\IOverviewProvider;
use Fluxlabs\Assessment\Test\Modules\Player\IPlayerContext;
use Fluxlabs\Assessment\Test\Modules\Player\Page\TestOverview\OverviewState;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;
use PHPUnit\Util\Test;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Application\Service\QuestionService;
use srag\asq\Domain\QuestionDto;
use srag\asq\Infrastructure\Persistence\Projection\QuestionListItemAr;

/**
 * Class AssessmentTestStorage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentTestContext implements IPlayerContext, IOverviewProvider
{
    private TestRunnerService $service;

    private Uuid $result_id;

    private Uuid $current_question_id;
    private ?Uuid $next_question;
    private ?Uuid $previous_question;

    private QuestionDto $current_question;
    private ?AbstractValueObject $current_answer;

    private QuestionService $question_service;

    /**
     * @var OverviewState[]
     */
    private array $overview_state = [];

    public function __construct(Uuid $result_id, ?Uuid $current_question_id, TestRunnerService $service)
    {
        global $ASQDIC;

        $this->result_id = $result_id;
        $this->service = $service;
        $this->question_service = $ASQDIC->asq()->question();

        $questions = $this->service->getQuestions($this->result_id);

        if ($current_question_id === null) {
            $this->current_question_id = $questions[0]->getQuestionId();
        }
        else {
            $this->current_question_id = $current_question_id;
        }

        foreach ($questions as $key => $definition) {
            $question = QuestionListItemAr::where(['question_id' => $definition->getQuestionId()->toString()])->first();
            $answer = $this->service->getItemResult($this->result_id, $definition->getQuestionId())->getAnswer();

            if ($this->current_question_id->equals($definition->getQuestionId())) {
                $this->previous_question = $key > 0 ? $questions[$key - 1]->getQuestionId() : null;
                if ($definition->getRevisionName()) {
                    $this->current_question = $this->question_service->getQuestionRevision($definition->getQuestionId(), $definition->getRevisionName());
                }
                else {
                    $this->current_question = $this->question_service->getQuestionByQuestionId($this->current_question_id);
                }
                $this->current_answer = $answer;
                $this->next_question = $key < (count($questions) - 1) ? $questions[$key + 1]->getQuestionId() : null;;
            }

            $this->overview_state[] =
                new OverviewState(
                    $answer ? OverviewState::STATE_ANSWERED : OverviewState::STATE_OPEN,
                    $question->getTitle(),
                    $definition->getQuestionId()
                );
        }
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

    public function getOverview(): array
    {
        return $this->overview_state;
    }
}