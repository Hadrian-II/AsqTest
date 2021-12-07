<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Command\AbstractCommand;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AddAnswerCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AddAnswerCommand extends AbstractCommand
{
    public Uuid $result_uuid;

    public Uuid $question_id;

    public ?AbstractValueObject $answer;

    public function __construct(Uuid $result_uuid, Uuid $question_id, ?AbstractValueObject $answer)
    {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->answer = $answer;
        parent::__construct();
    }

    public function getResultUuid() : Uuid
    {
        return $this->result_uuid;
    }

    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    public function getAnswer() : ?AbstractValueObject
    {
        return $this->answer;
    }
}
