<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Model;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Test\Domain\Result\Event\AnswerSetEvent;
use srag\asq\Test\Domain\Result\Event\AssessmentResultInitiatedEvent;
use srag\asq\Domain\Model\Hint\QuestionHint;
use srag\asq\Test\Domain\Result\Event\HintReceivedEvent;
use srag\asq\Test\Domain\Result\Event\AssessmentResultSubmittedEvent;
use srag\asq\Test\Domain\Result\Event\ScoreSetEvent;
use srag\asq\Test\Domain\Result\Event\ScoringFinishedEvent;
use srag\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Factory;

/**
 * Class AssessmentResult
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResult extends AbstractAggregateRoot
{
    /**
     * @var AssessmentResultContext
     */
    protected $context;

    /**
     * @var ItemResult[]
     */
    protected $results;

    /**
     * @var string
     */
    protected $status;

    /**
     * @param Uuid $id
     * @param AssessmentResultContext $context
     * @param array $question_ids
     * @param int $user_id
     * @return AssessmentResult
     */
    public static function create(Uuid $id, AssessmentResultContext $context, array $question_ids, int $user_id) : AssessmentResult
    {
        $result = new AssessmentResult();
        $occured_on = new ilDateTime(time(), IL_CAL_UNIX);
        $result->ExecuteEvent(
            new AggregateCreatedEvent(
                $id,
                $occured_on,
                $user_id
            )
        );
        $result->ExecuteEvent(
            new AssessmentResultInitiatedEvent(
                $id,
                $occured_on,
                $user_id,
                $context,
                $question_ids
            )
        );

        return $result;
    }

    /**
     * @param AssessmentResultInitiatedEvent $event
     */
    protected function applyAssessmentResultInitiatedEvent(AssessmentResultInitiatedEvent $event)
    {
        $this->context = $event->getContext();
        $this->status = SessionStatus::INITIAL;
        $this->results = [];

        $ix = 1;
        foreach ($event->getQuestions() as $question_id) {
            $this->results[$question_id->toString()] = new ItemResult($question_id, $ix);
            $ix += 1;
        }
    }

    /**
     * @param AnswerSetEvent $event
     */
    protected function applyAnswerSetEvent(AnswerSetEvent $event)
    {
        $result = $this->results[$event->getQuestionId()->toString()];
        $this->results[$event->getQuestionId()->toString()] = $result->withAnswer($event->getAnswer());
    }

    /**
     * @param HintReceivedEvent $event
     */
    protected function applyHintReceivedEvent(HintReceivedEvent $event)
    {
        $result = $this->results[$event->getQuestionId()->toString()];
        $this->results[$event->getQuestionId()->toString()] = $result->withAddedHint($event->getHint());
    }

    /**
     * @param AssessmentResultSubmittedEvent $event
     */
    protected function applyAssessmentResultSubmittedEvent(AssessmentResultSubmittedEvent $event)
    {
        $this->status = SessionStatus::PENDING_RESPONSE_PROCESSING;
    }

    /**
     * @param ScoreSetEvent $event
     */
    protected function applyScoreSetEvent(ScoreSetEvent $event)
    {
        $result = $this->results[$event->getQuestionId()->toString()];
        $this->results[$event->getQuestionId()->toString()] = $result->withScore($event->getScore());
    }

    /**
     * @param ScoringFinishedEvent $event
     */
    protected function applyScoringFinishedEvent(ScoringFinishedEvent $event)
    {
        $this->status = SessionStatus::FINAL;
    }

    /**
     * @return AssessmentResultContext
     */
    public function getContext() : AssessmentResultContext
    {
        return $this->context;
    }

    /**
     * @param Uuid $question_id
     * @throws AsqException
     * @return ItemResult|NULL
     */
    public function getItemResult(Uuid $question_id) : ?ItemResult
    {
        if (array_key_exists($question_id->toString(), $this->results)) {
            return $this->results[$question_id->toString()];
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    /**
     * @param Uuid $question_id
     * @param AbstractValueObject $answer
     * @param int $initiating_user_id
     * @throws AsqException
     */
    public function setAnswer(Uuid $question_id, AbstractValueObject $answer, int $initiating_user_id)
    {
        if ($this->status === SessionStatus::PENDING_RESPONSE_PROCESSING ||
            $this->status === SessionStatus::FINAL) {
            throw new AsqException('Cant change Answer on Submitted AssessmentResult');
        }

        if (array_key_exists($question_id->toString(), $this->results)) {
            $this->ExecuteEvent(new AnswerSetEvent(
                $this->getAggregateId(),
                new ilDateTime(time(), IL_CAL_UNIX),
                $initiating_user_id,
                $question_id,
                $answer
            ));
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    /**
     * @param Uuid $question_id
     * @param ItemScore $score
     * @param int $initiating_user_id
     * @throws AsqException
     */
    public function setScore(Uuid $question_id, ItemScore $score, int $initiating_user_id)
    {
        if ($this->status !== SessionStatus::PENDING_RESPONSE_PROCESSING) {
            throw new AsqException('Scoring only possible on submitted result with unfinished scoring');
        }

        if (array_key_exists($question_id->toString(), $this->results)) {
            $this->ExecuteEvent(new ScoreSetEvent(
                $this->getAggregateId(),
                new ilDateTime(time(), IL_CAL_UNIX),
                $initiating_user_id,
                $question_id,
                $score
            ));
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    /**
     * @param Uuid $question_id
     * @param QuestionHint $hint
     * @param int $initiating_user_id
     * @throws AsqException
     */
    public function addHint(Uuid $question_id, QuestionHint $hint, int $initiating_user_id)
    {
        if ($this->status === SessionStatus::PENDING_RESPONSE_PROCESSING ||
            $this->status === SessionStatus::FINAL) {
            throw new AsqException('Cant add Hint to Submitted AssessmentResult');
        }

        if (array_key_exists($question_id->toString(), $this->results)) {
            $this->ExecuteEvent(
                new HintReceivedEvent(
                    $this->getAggregateId(),
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $initiating_user_id,
                    $question_id,
                    $hint
                )
            );
        } else {
            throw new AsqException('Question is not part of current Assesment');
        }
    }

    /**
     * @param int $initiating_user_id
     * @throws AsqException
     */
    public function submitResult(int $initiating_user_id)
    {
        if ($this->status === SessionStatus::PENDING_RESPONSE_PROCESSING ||
            $this->status === SessionStatus::FINAL) {
            throw new AsqException('Cant submit AssessmentResult twice');
        }

        $this->ExecuteEvent(new AssessmentResultSubmittedEvent(
            $this->getAggregateId(),
            new ilDateTime(time(), IL_CAL_UNIX),
            $initiating_user_id
        ));
    }

    /**
     * @param int $initiating_user_id
     * @throws AsqException
     */
    public function finishScoring(int $initiating_user_id)
    {
        if ($this->status !== SessionStatus::PENDING_RESPONSE_PROCESSING) {
            throw new AsqException('Can only finish scoring on submited result with open scoring');
        }

        $this->ExecuteEvent(new ScoringFinishedEvent(
            $this->getAggregateId(),
            new ilDateTime(time(), IL_CAL_UNIX),
            $initiating_user_id
        ));
    }

    /**
     * @return Uuid[]
     */
    public function getQuestions() : array
    {
        $factory = new Factory();

        return array_map(function($id) use ($factory) {
            return $factory->fromString($id);
        }, array_keys($this->results));
    }
}
