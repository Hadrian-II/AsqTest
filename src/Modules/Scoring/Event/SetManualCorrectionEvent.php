<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Scoring\Event;

use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemScore;
use Fluxlabs\Assessment\Tools\Event\Event;
use Fluxlabs\Assessment\Tools\Event\IEventUser;
use ILIAS\Data\UUID\Uuid;
use ILIAS\UI\Component\Item\Item;

/**
 * Class SetManualCorrection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SetManualCorrectionEvent extends Event
{
    private Uuid $run_id;

    private Uuid $question_id;

    private ItemScore $score;

    public function __construct(IEventUser $sender, Uuid $run_id, Uuid $question_id, ItemScore $score)
    {
        parent::__construct($sender);

        $this->run_id = $run_id;
        $this->question_id = $question_id;
        $this->score = $score;
    }

    public function getRunId(): Uuid
    {
        return $this->run_id;
    }

    public function getQuestionId(): Uuid
    {
        return $this->question_id;
    }

    public function getScore(): ItemScore
    {
        return $this->score;
    }
}