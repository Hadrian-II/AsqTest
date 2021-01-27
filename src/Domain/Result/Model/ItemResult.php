<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Model;

use ilDateTime;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Domain\Model\Hint\QuestionHint;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Domain\Model\Hint\QuestionHints;
use ILIAS\Data\UUID\Uuid;
use ILIAS\Data\UUID\Factory;

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
     * @var Uuid
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
     * @param Uuid $question_id
     * @param int $sequence_index
     */
    public function __construct(Uuid $question_id = null, int $sequence_index = null)
    {
        $this->question_id = $question_id;
        $this->sequence_index = $sequence_index;
        $this->session_status = SessionStatus::INITIAL;
        $this->datestamp = new ilDateTime(time(), IL_CAL_UNIX);
        $this->hints = new QuestionHints();
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
        $clone->hints = new Questionhints($hints);
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
     * @return Uuid
     */
    public function getQuestionId() : Uuid
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

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return \ILIAS\Data\UUID\Uuid|mixed
     */
    protected static function deserializeValue(string $key, $value)
    {
        if ($key === 'question_id') {
            $factory = new Factory();
            return $factory->fromString($value);
        }
        //virtual method
        return $value;
    }
}
