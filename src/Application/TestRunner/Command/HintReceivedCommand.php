<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;
use srag\asq\Domain\Model\Hint\QuestionHint;

/**
 * Class HintReceivedCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class HintReceivedCommand extends AbstractCommand
{
    /**
     * @var Uuid
     */
    public $result_uuid;

    /**
     * @var Uuid
     */
    public $question_id;

    /**
     * @var QuestionHint
     */
    public $hint;

    /**
     * @param Uuid $result_uuid
     * @param int $user_id
     * @param Uuid $question_id
     * @param QuestionHint $hint
     */
    public function __construct(Uuid $result_uuid, int $user_id, Uuid $question_id, QuestionHint $hint)
    {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->hint = $hint;
        parent::__construct($user_id);
    }

    /**
     * @return Uuid
     */
    public function getResultUuid() : Uuid
    {
        return $this->result_uuid;
    }

    /**
     * @return Uuid
     */
    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    /**
     * @return QuestionHint
     */
    public function getHint() : QuestionHint
    {
        return $this->hint;
    }
}
