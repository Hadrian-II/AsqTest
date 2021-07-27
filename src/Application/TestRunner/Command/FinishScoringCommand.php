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
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class FinishScoringCommand extends AbstractCommand
{
    public Uuid $result_uuid;

    public function __construct(Uuid $result_uuid, int $user_id)
    {
        $this->result_uuid = $result_uuid;
        parent::__construct($user_id);
    }

    public function getResultUuid() : Uuid
    {
        return $this->result_uuid;
    }
}
