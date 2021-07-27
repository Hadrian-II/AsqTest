<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Result\Model;

use ilDateTime;
use ILIAS\UI\Component\Item\Item;
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
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ItemResult extends AbstractValueObject
{
    protected ?Uuid $question_id;

    protected ?int $sequence_index;

    protected ilDateTime $datestamp;

    protected string $session_status;

    protected ?AbstractValueObject $answer;

    protected ItemScore $score;

    protected QuestionHints $hints;

    protected string $candidate_comment;

    public function __construct(Uuid $question_id = null, int $sequence_index = null)
    {
        $this->question_id = $question_id;
        $this->sequence_index = $sequence_index;
        $this->session_status = SessionStatus::INITIAL;
        $this->datestamp = new ilDateTime(time(), IL_CAL_UNIX);
        $this->hints = new QuestionHints();
    }

    public function withAnswer(AbstractValueObject $answer) : ItemResult
    {
        $clone = clone $this;
        $clone->answer = $answer;
        $clone->session_status = SessionStatus::PENDING_SUBMISSION;
        return $clone;
    }

    public function withScore(ItemScore $score) : ItemResult
    {
        $clone = clone $this;
        $clone->score = $score;
        $clone->session_status = SessionStatus::FINAL;
        return $clone;
    }

    public function withAddedHint(QuestionHint $hint) : ItemResult
    {
        $clone = clone $this;
        $hints = $this->hints->getHints();
        $hints[] = $hint;
        $clone->hints = new Questionhints($hints);
        return $clone;
    }

    public function withComment(string $comment) : ItemResult
    {
        $clone = clone $this;
        $clone->candidate_comment = $comment;
        return $clone;
    }

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

    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    public function getSequenceIndex() : int
    {
        return $this->sequence_index;
    }

    public function getDatestamp() : ilDateTime
    {
        return $this->datestamp;
    }

    public function getSessionStatus() : string
    {
        return $this->session_status;
    }

    public function getAnswer() : ?AbstractValueObject
    {
        return $this->answer;
    }

    public function getScore() : ?ItemScore
    {
        return $this->score;
    }

    public function getHints() : QuestionHints
    {
        return $this->hints;
    }

    public function hasHints() : bool
    {
        return count($this->hints->getHints()) > 0;
    }

    public function getCandidateComment() : string
    {
        return $this->candidate_comment;
    }

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
