<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class FinishScoringCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class FinishScoringCommand extends AbstractCommand
{
    /**
     * @var Uuid
     */
    public $result_uuid;

    /**
     * @param Uuid $result_uuid
     * @param int $user_id
     */
    public function __construct(Uuid $result_uuid, int $user_id)
    {
        $this->result_uuid = $result_uuid;
        parent::__construct($user_id);
    }

    /**
     * @return Uuid
     */
    public function getResultUuid() : Uuid
    {
        return $this->result_uuid;
    }
}
