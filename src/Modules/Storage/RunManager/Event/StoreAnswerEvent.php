<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\RunManager\Event;

use Fluxlabs\Assessment\Tools\Event\Event;
use Fluxlabs\Assessment\Tools\Event\IEventUser;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class StoreAnswerEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class StoreAnswerEvent extends Event
{
    private Uuid $question_id;

    private ?AbstractValueObject $answer;

    public function __construct(IEventUser $sender, Uuid $question_id, ?AbstractValueObject $answer)
    {
        parent::__construct($sender);

        $this->question_id = $question_id;
        $this->answer = $answer;
    }

    public function getQuestionId(): Uuid
    {
        return $this->question_id;
    }

    public function getAnswer(): ?AbstractValueObject
    {
        return $this->answer;
    }
}