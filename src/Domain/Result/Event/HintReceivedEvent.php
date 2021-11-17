<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Event;

use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use srag\asq\Domain\Model\Hint\QuestionHint;
use ILIAS\Data\UUID\Uuid;
use DateTimeImmutable;
use ILIAS\Data\UUID\Factory;

/**
 * Class HintReceivedEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class HintReceivedEvent extends AbstractDomainEvent
{
    const KEY_QUESTION_ID = 'quid';
    const KEY_HINT = 'hint';

    protected ?Uuid $question_id;

    protected ?QuestionHint $hint;

    public function __construct(
        Uuid $aggregate_id,
        DateTimeImmutable $occured_on,
        Uuid $question_id = null,
        QuestionHint $hint = null
    ) {
        $this->question_id = $question_id;
        $this->hint = $hint;
        parent::__construct($aggregate_id, $occured_on);
    }

    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    public function getHint() : QuestionHint
    {
        return $this->hint;
    }

    public function getEventBody() : string
    {
        $body = [];
        $body[self::KEY_QUESTION_ID] = $this->question_id->toString();
        $body[self::KEY_HINT] = $this->hint;
        return json_encode($body);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();

        $body = json_decode($event_body, true);
        $this->question_id = $factory->fromString($body[self::KEY_QUESTION_ID]);
        $this->hint = QuestionHint::createFromArray($body[self::KEY_HINT]);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
