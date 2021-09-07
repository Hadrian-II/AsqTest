<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;
use srag\asq\Domain\Model\Hint\QuestionHint;

/**
 * Class HintReceivedCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class HintReceivedCommand extends AbstractCommand
{
    public Uuid $result_uuid;

    public Uuid $question_id;

    public QuestionHint $hint;

    public function __construct(Uuid $result_uuid, int $user_id, Uuid $question_id, QuestionHint $hint)
    {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->hint = $hint;
        parent::__construct($user_id);
    }

    public function getResultUuid() : Uuid
    {
        return $this->result_uuid;
    }

    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    public function getHint() : QuestionHint
    {
        return $this->hint;
    }
}
