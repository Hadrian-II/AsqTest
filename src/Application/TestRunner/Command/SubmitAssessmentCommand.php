<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Command\AbstractCommand;

/**
 * Class SubmitAssessmentCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SubmitAssessmentCommand extends AbstractCommand
{
    public Uuid $result_uuid;

    public function __construct(Uuid $result_uuid)
    {
        $this->result_uuid = $result_uuid;
        parent::__construct();
    }

    public function getResultUuid() : Uuid
    {
        return $this->result_uuid;
    }
}
