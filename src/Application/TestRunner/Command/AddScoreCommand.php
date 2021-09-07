<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;
use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemScore;

/**
 * Class AddScoreCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AddScoreCommand extends AbstractCommand
{
    public Uuid $result_uuid;

    public Uuid $question_id;

    public ItemScore $score;

    public function __construct(Uuid $result_uuid, int $user_id, Uuid $question_id, ItemScore $score)
    {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->score = $score;
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

    public function getScore() : ItemScore
    {
        return $this->score;
    }
}
