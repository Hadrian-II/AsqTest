<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AddAnswerCommand
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AddAnswerCommand extends AbstractCommand
{
    public Uuid $result_uuid;

    public Uuid $question_id;

    public  AbstractValueObject$answer;

    public function __construct(Uuid $result_uuid, int $user_id, Uuid $question_id, AbstractValueObject $answer)
    {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->answer = $answer;
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

    public function getAnswer() : AbstractValueObject
    {
        return $this->answer;
    }
}
