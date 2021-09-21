<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use Fluxlabs\CQRS\Command\AbstractCommand;

/**
 * Class PerformAutomaticScoringCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class PerformAutomaticScoringCommand extends AbstractCommand
{
    public string $result_uuid;

    public function __construct(string $result_uuid, int $user_id)
    {
        $this->result_uuid = $result_uuid;
        parent::__construct($user_id);
    }

    public function getResultUuid() : string
    {
        return $this->result_uuid;
    }
}
