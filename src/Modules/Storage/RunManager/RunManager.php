<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\RunManager;

use DateTimeImmutable;
use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Domain\Instance\Model\AssessmentInstance;
use Fluxlabs\Assessment\Test\Domain\Instance\Model\AssessmentInstanceConfiguration;
use Fluxlabs\Assessment\Test\Domain\Instance\Model\AssessmentInstanceRepository;
use Fluxlabs\Assessment\Test\Domain\Instance\Model\AssessmentInstanceRun;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\InstanceState;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\RunState;
use Fluxlabs\Assessment\Test\Domain\Instance\Persistence\Projections\TestState;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultContext;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\AssessmentTestContext;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\StoreAnswerEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\SubmitTestEvent;
use Fluxlabs\Assessment\Test\Modules\Storage\RunManager\Event\CreateInstanceEvent;
use Fluxlabs\Assessment\Tools\DIC\UserTrait;
use Fluxlabs\Assessment\Tools\Domain\IObjectAccess;
use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use Fluxlabs\Assessment\Tools\Event\IEventQueue;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use srag\asq\Application\Exception\AsqException;

/**
 * Class AssessmentTestStorage
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RunManager extends AbstractAsqModule
{
    use UserTrait;

    private Factory $factory;
    private TestRunnerService $runner_service;

    private ?AssessmentInstance $instance = null;

    private Uuid $test_id;

    private AssessmentInstanceRepository $repository;

    private ?TestState $current_test_state = null;

    private ?InstanceState $current_instance_state = null;

    private ?RunState  $current_run_state = null;
    private bool $no_current_run_found = false;

    public function __construct(IEventQueue $event_queue, IObjectAccess $access, Uuid $test_id)
    {
        $this->runner_service = new TestRunnerService();
        $this->factory = new Factory();
        $this->repository = new AssessmentInstanceRepository();
        $this->test_id = $test_id;

        parent::__construct($event_queue, $access);
    }

    public function getPlayerContext(?Uuid $current_question = null) : AssessmentTestContext
    {
        return new AssessmentTestContext($this->getCurrentRunId(), $current_question, $this->runner_service);
    }

    private function getCurrentRunId() : Uuid
    {
        if ($this->getCurrentRunState() === null) {
            $this->createNewRun();
        }

        return $this->getCurrentRunState()->getAggregateId();
    }

    private function createNewRun() : void
    {
        if(!$this->instance->canUserStartRun($this->getCurrentUser())) {
            throw new AsqException("User cannot start another run");
        }

        $run_id = $this->runner_service->createTestRun($this->createResultContext($this->getCurrentUser()), $this->access->getStorage()->getTestQuestions());
        $this->instance->startRun($this->getCurrentUser(), $run_id);

        $this->current_run_state = new RunState();
        $this->current_run_state->setData($run_id, $this->getCurrentInstanceState(), new DateTimeImmutable(), $this->getCurrentUser());
        $this->current_run_state->create();
    }

    private function createResultContext(int $user_id) : AssessmentResultContext
    {
        return new AssessmentResultContext(
            $user_id,
            'TODO TEST',
            1,
            $this->test_id
        );
    }

    public function processEvent(object $event) : void
    {
        if (get_class($event) === StoreAnswerEvent::class) {
            $this->processStoreAnswerEvent($event->getQuestionId(), $event->getAnswer());
        }

        if (get_class($event) === SubmitTestEvent::class) {
            $this->processSubmitTestEvent();
        }

        if (get_class($event) === CreateInstanceEvent::class) {
            $this->processCreateInstanceEvent();
        }
    }

    private function processStoreAnswerEvent(Uuid $question_id, AbstractValueObject $answer)
    {
        $this->runner_service->addAnswer(
            $this->getCurrentRunId(),
            $question_id,
            $answer
        );
    }

    private function processSubmitTestEvent() : void
    {
        $run_id = $this->getCurrentRunId();
        $this->runner_service->submitTestRun($run_id);

        $this->instance->submitRun($this->getCurrentUser(), $run_id);

        $this->getCurrentRunState()->setState(AssessmentInstanceRun::STATE_SUBMITTED);
        $this->getCurrentRunState()->save();
    }

    private function processCreateInstanceEvent() : void
    {
        $instance_id = $this->factory->uuid4();
        $this->instance = AssessmentInstance::create(
            $instance_id,
            AssessmentInstanceConfiguration::create(
                $this->test_id,
                new DateTimeImmutable('2000-01-01'),
                new DateTimeImmutable('2100-01-01')
            )
        );
        $this->repository->save($this->instance);

        $instance_state = new InstanceState();
        $instance_state->setData(
            $instance_id,
            $this->test_id,
            $this->getCurrentTestState()->getId(),
            $this->instance->getConfig()->getStartTime(),
            $this->instance->getConfig()->getEndTime()
        );
        $instance_state->create();
        $this->current_instance_state = $instance_state;

        $this->getCurrentTestState()->setCurrentInstance($instance_state);
        $this->getCurrentTestState()->save();
    }

    private function getCurrentInstance() : ?AssessmentInstance
    {
        if ($this->instance === null &&
            $this->getCurrentTestState()->getCurrentInstanceId() !== null) {
            $this->instance = $this->repository->getAggregateRootById($this->getCurrentTestState()->getCurrentInstanceId());
        }

        return $this->instance;
    }

    private function getCurrentTestState() : TestState
    {
        if ($this->current_test_state === null) {
            $this->current_test_state = TestState::where(['aggregate_id' => $this->test_id->toString()])->first();
        }

        if ($this->current_test_state === null) {
            $this->current_test_state = new TestState();
            $this->current_test_state->setAggregateId($this->test_id);
            $this->current_test_state->create();
        }

        return $this->current_test_state;
    }

    private function getCurrentInstanceState() : ?InstanceState
    {
        if ($this->getCurrentTestState()->getCurrentInstanceId() === null) {
            return null;
        }

        if ($this->current_instance_state === null) {
            $this->current_instance_state = InstanceState::where(['id' => $this->getCurrentTestState()->getCurrentInstanceStateId()])->first();
        }

        return $this->current_instance_state;
    }

    private function getCurrentRunState() : ?RunState
    {
        if ($this->no_current_run_found ||
            $this->getCurrentInstanceState() === null) {
            return null;
        }

        if ($this->current_run_state === null) {
            $this->current_run_state = RunState::where(
                [
                    'instancestate_id' => $this->getCurrentInstanceState()->getId(),
                    'user_id' => $this->getCurrentUser(),
                    'state' => AssessmentInstanceRun::STATE_OPEN
                ])->first();

            if ($this->current_run_state === null) {
                $this->no_current_run_found = true;
            }
        }

        return $this->current_run_state;
    }
}