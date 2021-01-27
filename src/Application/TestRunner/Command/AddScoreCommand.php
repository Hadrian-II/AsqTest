<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;
use srag\asq\Test\Domain\Result\Model\ItemScore;

/**
 * Class AddScoreCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddScoreCommand extends AbstractCommand
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
     * @var ItemScore
     */
    public $score;

    /**
     * @param string $result_uuid
     * @param int $user_id
     * @param string $question_id
     * @param ItemScore $score
     */
    public function __construct(Uuid $result_uuid, int $user_id, Uuid $question_id, ItemScore $score)
    {
        $this->result_uuid = $result_uuid;
        $this->question_id = $question_id;
        $this->score = $score;
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
     * @return ItemScore
     */
    public function getScore() : ItemScore
    {
        return $this->score;
    }
}
