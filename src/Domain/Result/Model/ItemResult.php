<?php

namespace srag\asq\Test\Domain\Result\Model;

use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\Model\Answer\Answer;
use srag\asq\Domain\Model\Hint\QuestionHint;
use srag\asq\Application\Exception\AsqException;

/**
 * Class ItemResult
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class ItemResult extends AbstractValueObject {
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
     * @var Answer
     */
    protected $answer;
    
    /**
     * @var QuestionHint[]
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
    public static function create(string $question_id, int $sequence_index) : ItemResult {
        $object = new ItemResult();
        $object->question_id = $question_id;
        $object->sequence_index = $sequence_index;
        $object->session_status = SessionStatus::INITIAL;
        $object->datestamp = ilDateTime(time(), IL_CAL_UNIX);
        $object->hints = [];
        return $object;
    }
    
    /**
     * @param Answer $answer
     * @return ItemResult
     */
    public function withValue(Answer $answer) {
        $clone = clone $this;
        $clone->answer = $answer;
        $clone->session_status = SessionStatus::PENDING_SUBMISSION;
        return $clone;
    }
    
    /**
     * @param QuestionHint $hint
     * @return ItemResult
     */
    public function withAddedHint(QuestionHint $hint) : ItemResult {
        $clone = clone $this;
        $clone->hints[] = $hint;
        return $clone;
    }
    
    /**
     * @param string $comment
     * @return ItemResult
     */
    public function withComment(string $comment) : ItemResult {
        $clone = clone $this;
        $clone->candidate_comment = $comment;
        return $clone;
    }
    
    /**
     * @param string $status
     * @return ItemResult
     */
    public function withStatus(string $status) : ItemResult {
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
     * @return Answer
     */
    public function getAnswer() : Answer
    {
        return $this->answer;
    }

    /**
     * @return QuestionHint[]
     */
    public function getHints() : array
    {
        return $this->hints;
    }

    /**
     * @return string
     */
    public function getCandidateComment() : string
    {
        return $this->candidate_comment;
    }
}