<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Scoring\Event;

use Fluxlabs\Assessment\Tools\Event\Event;
use Fluxlabs\Assessment\Tools\Event\IEventUser;
use ILIAS\Data\UUID\Uuid;

/**
 * Class SubmitCorrection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SubmitCorrectionEvent extends Event
{
    private Uuid $run_id;

    public function __construct(IEventUser $sender, Uuid $run_id)
    {
        parent::__construct($sender);

        $this->run_id = $run_id;
    }

    public function getRunId(): Uuid
    {
        return $this->run_id;
    }
}