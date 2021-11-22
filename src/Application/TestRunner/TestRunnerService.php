<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner;

use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use Fluxlabs\CQRS\Command\CommandBus;
use Fluxlabs\CQRS\Command\CommandConfiguration;
use Fluxlabs\CQRS\Command\Access\OpenAccess;
use srag\asq\Application\Service\ASQService;
use srag\asq\Domain\Model\Hint\QuestionHint;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\AddAnswerCommand;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\AddAnswerCommandHandler;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\AddScoreCommand;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\AddScoreCommandHandler;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\FinishScoringCommand;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\HintReceivedCommand;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\HintReceivedCommandHandler;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\StartAssessmentCommand;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\StartAssessmentCommandHandler;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\SubmitAssessmentCommand;
use Fluxlabs\Assessment\Test\Application\TestRunner\Command\SubmitAssessmentCommandHandler;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultContext;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultRepository;
use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemResult;
use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemScore;

/**
 * Class TestRunnerService
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */

class TestRunnerService
{
    private CommandBus $command_bus;

    private AssessmentResultRepository $repo;

    public function __construct()
    {
        $this->command_bus = new CommandBus();

        $this->command_bus->registerCommand(new CommandConfiguration(
            AddAnswerCommand::class,
            new AddAnswerCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            StartAssessmentCommand::class,
            new StartAssessmentCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            SubmitAssessmentCommand::class,
            new SubmitAssessmentCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            HintReceivedCommand::class,
            new HintReceivedCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            AddScoreCommand::class,
            new AddScoreCommandHandler(),
            new OpenAccess()
        ));

        $this->repo = new AssessmentResultRepository();
    }

    public function createTestRun(AssessmentResultContext $context, array $question_ids) : Uuid
    {
        $uuid_factory = new Factory();

        $uuid = $uuid_factory->uuid4();

        // CreateQuestion.png
        $this->command_bus->handle(
            new StartAssessmentCommand(
                $uuid,
                $context,
                $question_ids
            )
        );

        return $uuid;
    }

    public function addAnswer(Uuid $uuid, Uuid $question_id, AbstractValueObject $answer) : void
    {
        $this->command_bus->handle(
            new AddAnswerCommand(
                $uuid,
                $question_id,
                $answer
            )
        );
    }

    public function hintRecieved(Uuid $uuid, Uuid $question_id, QuestionHint $hint) : void
    {
        $this->command_bus->handle(
            new HintReceivedCommand(
                $uuid,
                $question_id,
                $hint
            )
        );
    }

    public function submitTestRun(Uuid $uuid) : void
    {
        $this->command_bus->handle(
            new SubmitAssessmentCommand(
                $uuid,
            )
        );
    }

    public function finishScoring(Uuid $uuid) : void
    {
        $this->command_bus->handle(
            new FinishScoringCommand(
                $uuid,
            )
        );
    }

    public function addScore(Uuid $uuid, Uuid $question_id, ItemScore $score) : void
    {
        $this->command_bus->handle(
            new AddScoreCommand(
                $uuid,
                $question_id,
                $score
            )
        );
    }

    public function getItemResult(Uuid $uuid, Uuid $question_id) : ?ItemResult
    {
        $assessment_result = $this->repo->getAggregateRootById($uuid);

        return $assessment_result->getItemResult($question_id);
    }

    public function getFirstQuestionId(Uuid $uuid) : Uuid
    {
        $assessment_result = $this->repo->getAggregateRootById($uuid);

        return $assessment_result->getQuestions()[0];
    }

    public function getPreviousQuestionId(Uuid $uuid, Uuid $question_id) : ?Uuid
    {
        $questions = $this->repo->getAggregateRootById($uuid)->getQuestions();

        $current_id = array_search($question_id, $questions);

        if ($current_id > 0) {
            return $questions[$current_id - 1];
        } else {
            return null;
        }
    }

    /**
     * @param Uuid $uuid
     * @return Uuid[]
     */
    public function getAllQuestions(Uuid $uuid) : array
    {
        return $this->repo->getAggregateRootById($uuid)->getQuestions();
    }

    public function getNextQuestionId(Uuid $uuid, Uuid $question_id) : ?Uuid
    {
        $questions = $this->repo->getAggregateRootById($uuid)->getQuestions();

        $current_id = array_search($question_id, $questions);

        if (array_key_exists($current_id + 1, $questions)) {
            return $questions[$current_id + 1];
        } else {
            return null;
        }
    }

    public function getPoints(Uuid $uuid) : float
    {
        $assessment_result = $this->repo->getAggregateRootById($uuid);

        return $assessment_result->getPoints();
    }
}
