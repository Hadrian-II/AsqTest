<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Model;

use ILIAS\Data\UUID\Uuid;
use DateTimeImmutable;
use Fluxlabs\CQRS\Aggregate\AbstractAggregateRoot;
use Fluxlabs\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\asq\Application\Exception\AsqException;
use Fluxlabs\Assessment\Test\Domain\Result\Event\AnswerSetEvent;
use Fluxlabs\Assessment\Test\Domain\Result\Event\AssessmentResultInitiatedEvent;
use srag\asq\Domain\Model\Hint\QuestionHint;
use Fluxlabs\Assessment\Test\Domain\Result\Event\HintReceivedEvent;
use Fluxlabs\Assessment\Test\Domain\Result\Event\AssessmentResultSubmittedEvent;
use Fluxlabs\Assessment\Test\Domain\Result\Event\ScoreSetEvent;
use Fluxlabs\Assessment\Test\Domain\Result\Event\ScoringFinishedEvent;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Factory;

/**
 * Class AssessmentResult
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResult extends AbstractAggregateRoot
{
    protected AssessmentResultContext $context;

    /**
     * @var ItemResult[]
     */
    protected array $results;

    /**
     * @var QuestionDefinition[]
     */
    protected array $questions = [];

    protected string $status;

    public static function create(
        Uuid $id,
        AssessmentResultContext $context,
        array $question_ids) : AssessmentResult
    {
        $result = new AssessmentResult();
        $occurred_on = new DateTimeImmutable();
        $result->ExecuteEvent(
            new AggregateCreatedEvent(
                $id,
                $occurred_on,
            )
        );
        $result->ExecuteEvent(
            new AssessmentResultInitiatedEvent(
                $id,
                $occurred_on,
                $context,
                $question_ids
            )
        );

        return $result;
    }

    protected function applyAssessmentResultInitiatedEvent(AssessmentResultInitiatedEvent $event) : void
    {
        $this->context = $event->getContext();
        $this->status = SessionStatus::INITIAL;
        $this->questions = $event->getQuestions();

        $this->results = [];
        $ix = 1;
        foreach ($this->questions as $question) {
            $this->results[$question->getQuestionId()->toString()] = new ItemResult($question->getQuestionId(), $ix);
            $ix += 1;
        }
    }

    protected function applyAnswerSetEvent(AnswerSetEvent $event) : void
    {
        $result = $this->results[$event->getQuestionId()->toString()];
        $this->results[$event->getQuestionId()->toString()] = $result->withAnswer($event->getAnswer());
    }

    protected function applyHintReceivedEvent(HintReceivedEvent $event) : void
    {
        $result = $this->results[$event->getQuestionId()->toString()];
        $this->results[$event->getQuestionId()->toString()] = $result->withAddedHint($event->getHint());
    }

    protected function applyAssessmentResultSubmittedEvent(AssessmentResultSubmittedEvent $event) : void
    {
        $this->status = SessionStatus::PENDING_RESPONSE_PROCESSING;
    }

    protected function applyScoreSetEvent(ScoreSetEvent $event) : void
    {
        $result = $this->results[$event->getQuestionId()->toString()];
        $this->results[$event->getQuestionId()->toString()] = $result->withScore($event->getScore());
    }

    protected function applyScoringFinishedEvent(ScoringFinishedEvent $event) : void
    {
        $this->status = SessionStatus::FINAL;
    }

    public function getContext() : AssessmentResultContext
    {
        return $this->context;
    }

    public function getItemResult(Uuid $question_id) : ?ItemResult
    {
        if (array_key_exists($question_id->toString(), $this->results)) {
            return $this->results[$question_id->toString()];
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    public function setAnswer(Uuid $question_id, AbstractValueObject $answer) : void
    {
        if ($this->status === SessionStatus::PENDING_RESPONSE_PROCESSING ||
            $this->status === SessionStatus::FINAL) {
            throw new AsqException('Cant change Answer on Submitted AssessmentResult');
        }

        if (array_key_exists($question_id->toString(), $this->results)) {

            $old_answer = $this->getItemResult($question_id)->getAnswer();

            if (!AbstractValueObject::isNullableEqual($answer, $old_answer))
            {
                $this->ExecuteEvent(new AnswerSetEvent(
                    $this->getAggregateId(),
                    new DateTimeImmutable(),
                    $question_id,
                    $answer
                ));
            }
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    public function setScore(Uuid $question_id, ItemScore $score) : void
    {
        if ($this->status !== SessionStatus::PENDING_RESPONSE_PROCESSING) {
            throw new AsqException('Scoring only possible on submitted result with unfinished scoring');
        }

        if (array_key_exists($question_id->toString(), $this->results)) {
            $this->ExecuteEvent(new ScoreSetEvent(
                $this->getAggregateId(),
                new DateTimeImmutable(),
                $question_id,
                $score
            ));
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    public function addHint(Uuid $question_id, QuestionHint $hint) : void
    {
        if ($this->status === SessionStatus::PENDING_RESPONSE_PROCESSING ||
            $this->status === SessionStatus::FINAL) {
            throw new AsqException('Cant add Hint to Submitted AssessmentResult');
        }

        if (array_key_exists($question_id->toString(), $this->results)) {
            $this->ExecuteEvent(
                new HintReceivedEvent(
                    $this->getAggregateId(),
                    new DateTimeImmutable(),
                    $question_id,
                    $hint
                )
            );
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    public function submitResult() : void
    {
        if ($this->status === SessionStatus::PENDING_RESPONSE_PROCESSING ||
            $this->status === SessionStatus::FINAL) {
            throw new AsqException('Cant submit AssessmentResult twice');
        }

        $this->ExecuteEvent(new AssessmentResultSubmittedEvent(
            $this->getAggregateId(),
            new DateTimeImmutable()
        ));
    }

    public function finishScoring() : void
    {
        if ($this->status !== SessionStatus::PENDING_RESPONSE_PROCESSING) {
            throw new AsqException('Can only finish scoring on submited result with open scoring');
        }

        $this->ExecuteEvent(new ScoringFinishedEvent(
            $this->getAggregateId(),
            new DateTimeImmutable()
        ));
    }

    /**
     * @return QuestionDefinition[]
     */
    public function getQuestions() : array
    {
        return $this->questions;
    }

    public function getPoints() : float
    {
        return array_reduce($this->results, function(float $points, ItemResult $result) {
            $points += $result->getScore() ? $result->getScore()->getReachedScore() : 0;
            return $points;
        }, 0);
    }
}
