<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Result\Event;

use DateTimeImmutable;
use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultContext;
use ILIAS\Data\UUID\Uuid;
use ILIAS\Data\UUID\Factory;

/**
 * Class AssessmentResultInitiatedEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentResultInitiatedEvent extends AbstractDomainEvent
{
    const KEY_CONTEXT = 'context';
    const KEY_QUESTIONS = 'questions';

    protected ?AssessmentResultContext $context;

    /**
     * @var QuestionDefinition[]
     */
    protected ?array $questions;

    public function __construct(
        Uuid $aggregate_id,
        DateTimeImmutable $occured_on,
        AssessmentResultContext $context = null,
        array $questions = null
    ) {
        $this->context = $context;
        $this->questions = $questions;
        parent::__construct($aggregate_id, $occured_on);
    }

    public function getContext() : AssessmentResultContext
    {
        return $this->context;
    }

    /**
     * @return QuestionDefinition[]
     */
    public function getQuestions() : array
    {
        return $this->questions;
    }

    public function getEventBody() : string
    {
        $body = [];
        $body[self::KEY_CONTEXT] = $this->context;
        $body[self::KEY_QUESTIONS] = $this->questions;
        return json_encode($body);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();

        $body = json_decode($event_body, true);
        $this->questions = AbstractValueObject::createFromArray($body[self::KEY_QUESTIONS]);
        $this->context = AbstractValueObject::createFromArray($body[self::KEY_CONTEXT]);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
