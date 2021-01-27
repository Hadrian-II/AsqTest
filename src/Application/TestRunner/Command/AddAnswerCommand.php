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
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddAnswerCommand extends AbstractCommand
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
     * @var AbstractValueObject
     */
    public $answer;

    /**
     * @param string $assessment_name
     * @param string $question_id
     * @param AbstractValueObject $answer
     */
    public function __construct(Uuid $result_uuid, int $user_id, Uuid $question_id, AbstractValueObject $answer)
    {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->answer = $answer;
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
     * @return AbstractValueObject
     */
    public function getAnswer() : AbstractValueObject
    {
        return $this->answer;
    }
}
