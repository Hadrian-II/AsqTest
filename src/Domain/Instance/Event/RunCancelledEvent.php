<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use ILIAS\Data\UUID\Factory;

/**
 * Class RunCancelledEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RunCancelledEvent extends AbstractDomainEvent
{
    const KEY_RUN_ID = 'run_id';
    const KEY_USER_ID = 'user_id';

    protected ?Uuid $run_id;

    protected ?int $user_id;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occurred_on,
        ?Uuid $run_id = null,
        ?int $user_id = null
    ) {
        parent::__construct($aggregate_id, $occurred_on);
        $this->run_id = $run_id;
        $this->user_id = $user_id;
    }

    public function getRunId() : ?Uuid
    {
        return $this->run_id;
    }

    public function getUserId() : ?int
    {
        return $this->user_id;
    }

    public function getEventBody() : string
    {
        $body = [];
        $body[self::KEY_RUN_ID] = $this->run_id->toString();
        $body[self::KEY_USER_ID] = $this->user_id;
        return json_encode($body);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();

        $body = json_decode($event_body, true);

        $this->run_id = $factory->fromString($body[self::KEY_RUN_ID]);
        $this->user_id = $body[self::KEY_USER_ID];
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
