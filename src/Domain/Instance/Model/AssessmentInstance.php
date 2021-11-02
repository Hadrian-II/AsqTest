<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Model;

use Fluxlabs\CQRS\Event\DomainEvent;
use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use Fluxlabs\CQRS\Aggregate\AbstractAggregateRoot;
use Fluxlabs\CQRS\Event\Standard\AggregateCreatedEvent;

/**
 * Class AssessmentInstance
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentInstance extends AbstractAggregateRoot
{
    const KEY_CONFIG = 'config';

    /**
     * @var AssessmentInstanceRun[]
     */
    protected array $runs;

    protected AssessmentInstanceConfiguration $configuration;

    public static function create(
        Uuid $id,
        AssessmentInstanceConfiguration $configuration,
        int $user_id) : AssessmentInstance
    {
        $instance = new AssessmentInstance();
        $occurred_on = new ilDateTime(time(), IL_CAL_UNIX);
        $instance->ExecuteEvent(
            new AggregateCreatedEvent(
                $id,
                $occurred_on,
                $user_id,
                [
                    self::KEY_CONFIG => $configuration
                ]
            )
        );

        return $instance;
    }

    protected function applyAggregateCreatedEvent(DomainEvent $event) : void
    {
        parent::applyAggregateCreatedEvent($event);
        $this->configuration = $event->getAdditionalData()[self::KEY_CONFIG];
    }
}
