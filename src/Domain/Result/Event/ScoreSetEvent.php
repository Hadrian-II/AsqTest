<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Event;

use DateTimeImmutable;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemScore;
use ILIAS\Data\UUID\Uuid;
use ILIAS\Data\UUID\Factory;

/**
 * Class ScoreSetEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ScoreSetEvent extends AbstractDomainEvent
{
    const KEY_QUESTION_ID = 'quid';
    const KEY_SCORE = 'score';

    protected ?Uuid $question_id;

    protected ?ItemScore $score;

    public function __construct(
        Uuid $aggregate_id,
        DateTimeImmutable $occured_on,
        Uuid $question_id = null,
        ItemScore $score = null
    ) {
        $this->question_id = $question_id;
        $this->score = $score;
        parent::__construct($aggregate_id, $occured_on);
    }

    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    public function getScore() : ItemScore
    {
        return $this->score;
    }

    public function getEventBody() : string
    {
        $body = [];
        $body[self::KEY_QUESTION_ID] = $this->question_id->toString();
        $body[self::KEY_SCORE] = $this->score;
        return json_encode($body);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();

        $body = json_decode($event_body, true);
        $this->question_id = $factory->fromString($body[self::KEY_QUESTION_ID]);
        $this->score = ItemScore::createFromArray($body[self::KEY_SCORE]);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
