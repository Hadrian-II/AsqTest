<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Model;

use Fluxlabs\Assessment\Test\Domain\Instance\Event\RunCancelledEvent;
use Fluxlabs\Assessment\Test\Domain\Instance\Event\RunCorrectedEvent;
use Fluxlabs\Assessment\Test\Domain\Instance\Event\RunStartedEvent;
use Fluxlabs\Assessment\Test\Domain\Instance\Event\RunSubmitedEvent;
use Fluxlabs\CQRS\Event\DomainEvent;
use ILIAS\Data\UUID\Uuid;
use DateTimeImmutable;
use Fluxlabs\CQRS\Aggregate\AbstractAggregateRoot;
use Fluxlabs\CQRS\Event\Standard\AggregateCreatedEvent;
use srag\asq\Application\Exception\AsqException;

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
     * @var array<int, array<Uuid, AssessmentInstanceRun>>
     */
    protected array $runs = [];

    protected AssessmentInstanceConfiguration $configuration;

    public static function create(
        Uuid $id,
        AssessmentInstanceConfiguration $configuration) : AssessmentInstance
    {
        $instance = new AssessmentInstance();
        $occurred_on = new DateTimeImmutable();
        $instance->ExecuteEvent(
            new AggregateCreatedEvent(
                $id,
                $occurred_on,
                [
                    self::KEY_CONFIG => $configuration
                ]
            )
        );

        return $instance;
    }

    public function getConfig() : AssessmentInstanceConfiguration
    {
        return $this->configuration;
    }

    protected function applyAggregateCreatedEvent(DomainEvent $event) : void
    {
        parent::applyAggregateCreatedEvent($event);
        $this->configuration = $event->getAdditionalData()[self::KEY_CONFIG];
    }

    public function canUserStartRun(int $user_id) : bool
    {
        if (!$this->userHasAvailableTries($user_id))
        {
            return false;
        }

        return true;
    }

    public function startRun(int $user_id, Uuid $run_id) : void
    {
        if (!$this->userHasAvailableTries($user_id)) {
            throw new AsqException("User has no avalable tries");
        }

        $this->ExecuteEvent(
            new RunStartedEvent(
                $this->aggregate_id,
                new DateTimeImmutable(),
                $run_id,
                $user_id
            )
        );
    }

    protected function applyRunStartedEvent(RunStartedEvent $event) : void
    {
        $this->runs[$event->getUserId()][$event->getRunId()->toString()] = AssessmentInstanceRun::create($event->getUserId(), $event->getRunId());
    }

    public function cancelRun(int $user_id, Uuid $run_id) : void
    {
        $run = $this->getRun($user_id, $run_id);

        $this->ExecuteEvent(
            new RunCancelledEvent(
                $this->aggregate_id,
                new DateTimeImmutable(),
                $run_id,
                $user_id
            )
        );
    }

    protected function applyRunCancelledEvent(RunCancelledEvent $event) : void
    {
        $this->getRun($event->getUserId(), $event->getRunId())->setState(AssessmentInstanceRun::STATE_CANCELLED);
    }

    public function submitRun(int $user_id, Uuid $run_id) : void
    {
        $run = $this->getRun($user_id, $run_id);

        $this->ExecuteEvent(
            new RunSubmitedEvent(
                $this->aggregate_id,
                new DateTimeImmutable(),
                $run_id,
                $user_id
            )
        );
    }

    protected function applyRunSubmitedEvent(RunSubmitedEvent $event) : void
    {
        $this->getRun($event->getUserId(), $event->getRunId())->setState(AssessmentInstanceRun::STATE_SUBMITTED);
    }

    public function correctRun(int $user_id, Uuid $run_id) : void
    {
        $run = $this->getRun($user_id, $run_id);

        $this->ExecuteEvent(
            new RunCorrectedEvent(
                $this->aggregate_id,
                new DateTimeImmutable(),
                $run_id,
                $user_id
            )
        );
    }

    protected function applyRunCorrectedEvent(RunCorrectedEvent $event) : void
    {
        $this->getRun($event->getUserId(), $event->getRunId())->setState(AssessmentInstanceRun::STATE_CORRECTED);
    }

    private function userHasAvailableTries(int $user_id) : bool
    {
        // first try
        if (!array_key_exists($user_id, $this->runs)) {
            return true;
        }

        if ($this->configuration->getTries() === AssessmentInstanceConfiguration::UNLIMITED_TRIES) {
            return true;
        }

        return count($this->runs[$user_id]) < $this->configuration->getTries();
    }

    private function getRun(int $user_id, Uuid $run_id) : AssessmentInstanceRun
    {
        if (!array_key_exists($user_id, $this->runs) ||
            !array_key_exists($run_id->toString(), $this->runs[$user_id])) {
            throw new AsqException(
                sprintf(
                    'The run with id : "%s" does not exist for User: "%s"',
                    $run_id->toString(),
                    $user_id
                )
            );
        }

        return $this->runs[$user_id][$run_id->toString()];
    }
}
