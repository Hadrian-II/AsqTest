<?php

namespace srag\asq\Test\Domain\Result\Model;

use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\Model\Hint\QuestionHint;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\Model\Hint\QuestionHints;

/**
 * Class ItemResult
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class ItemResult extends AbstractValueObject
{
    /**
     * @var String
     */
    protected $question_id;

    /**
     * @var int
     */
    protected $sequence_index;

    /**
     * @var ilDateTime
     */
    protected $datestamp;

    /**
     * @var string
     */
    protected $session_status;

    /**
     * @var ?Answer
     */
    protected $answer;

    /**
     * @var ItemScore
     */
    protected $score;

    /**
     * @var QuestionHints
     */
    protected $hints;

    /**
     * @var string
     */
    protected $candidate_comment;

    /**
     * @param string $question_id
     * @param int $sequence_index
     * @return ItemResult
     */
    public static function create(string $question_id, int $sequence_index) : ItemResult
    {
        $object = new ItemResult();
        $object->question_id = $question_id;
        $object->sequence_index = $sequence_index;
        $object->session_status = SessionStatus::INITIAL;
        $object->datestamp = new ilDateTime(time(), IL_CAL_UNIX);
        $object->hints = QuestionHints::create([]);
        return $object;
    }

    /**
     * @param AbstractValueObject $answer
     * @return ItemResult
     */
    public function withAnswer(AbstractValueObject $answer)
    {
        $clone = clone $this;
        $clone->answer = $answer;
        $clone->session_status = SessionStatus::PENDING_SUBMISSION;
        return $clone;
    }

    /**
     * @param ItemScore $answer
     * @return ItemResult
     */
    public function withScore(ItemScore $score)
    {
        $clone = clone $this;
        $clone->score = $score;
        $clone->session_status = SessionStatus::FINAL;
        return $clone;
    }

    /**
     * @param QuestionHint $hint
     * @return ItemResult
     */
    public function withAddedHint(QuestionHint $hint) : ItemResult
    {
        $clone = clone $this;
        $hints = $this->hints->getHints();
        $hints[] = $hint;
        $clone->hints = Questionhints::create($hints);
        return $clone;
    }

    /**
     * @param string $comment
     * @return ItemResult
     */
    public function withComment(string $comment) : ItemResult
    {
        $clone = clone $this;
        $clone->candidate_comment = $comment;
        return $clone;
    }

    /**
     * @param string $status
     * @return ItemResult
     */
    public function withStatus(string $status) : ItemResult
    {
        if (SessionStatus::isValid()) {
            $clone = clone $this;
            $clone->session_status = $status;
            return $clone;
        } else {
            throw new AsqException(sprintf('Trying to set invalid Sessionstatus: "%s"', $status));
        }
    }

    /**
     * @return string
     */
    public function getQuestionId() : string
    {
        return $this->question_id;
    }

    /**
     * @return int
     */
    public function getSequenceIndex() : int
    {
        return $this->sequence_index;
    }

    /**
     * @return ilDateTime
     */
    public function getDatestamp() : ilDateTime
    {
        return $this->datestamp;
    }

    /**
     * @return string
     */
    public function getSessionStatus() : string
    {
        return $this->session_status;
    }

    /**
     * @return AbstractValueObject
     */
    public function getAnswer() : ?AbstractValueObject
    {
        return $this->answer;
    }

    /**
     * @return ItemScore|NULL
     */
    public function getScore() : ?ItemScore
    {
        return $this->score;
    }

    /**
     * @return QuestionHints
     */
    public function getHints() : QuestionHints
    {
        return $this->hints;
    }

    /**
     * @return bool
     */
    public function hasHints() : bool
    {
        return count($this->hints->getHints()) > 0;
    }

    /**
     * @return string
     */
    public function getCandidateComment() : string
    {
        return $this->candidate_comment;
    }
}
