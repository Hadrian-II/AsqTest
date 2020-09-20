<?php

namespace srag\asq\Test\Application\TestRunner;

use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Aggregate\AbstractValueObject;
use srag\CQRS\Command\CommandBus;
use srag\CQRS\Command\CommandConfiguration;
use srag\CQRS\Command\Access\OpenAccess;
use srag\asq\Application\Service\ASQService;
use srag\asq\Domain\Model\Hint\QuestionHint;
use srag\asq\Test\Application\TestRunner\Command\AddAnswerCommand;
use srag\asq\Test\Application\TestRunner\Command\AddAnswerCommandHandler;
use srag\asq\Test\Application\TestRunner\Command\AddScoreCommand;
use srag\asq\Test\Application\TestRunner\Command\AddScoreCommandHandler;
use srag\asq\Test\Application\TestRunner\Command\FinishScoringCommand;
use srag\asq\Test\Application\TestRunner\Command\HintReceivedCommand;
use srag\asq\Test\Application\TestRunner\Command\HintReceivedCommandHandler;
use srag\asq\Test\Application\TestRunner\Command\StartAssessmentCommand;
use srag\asq\Test\Application\TestRunner\Command\StartAssessmentCommandHandler;
use srag\asq\Test\Application\TestRunner\Command\SubmitAssessmentCommand;
use srag\asq\Test\Application\TestRunner\Command\SubmitAssessmentCommandHandler;
use srag\asq\Test\Domain\Result\Model\AssessmentResultContext;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;
use srag\asq\Test\Domain\Result\Model\ItemResult;
use srag\asq\Test\Domain\Result\Model\ItemScore;

/**
 * Class TestRunnerService
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */

class TestRunnerService extends ASQService
{
    /**
     * @var CommandBus
     */
    private $command_bus;

    /**
     * @return CommandBus
     */
    private function getCommandBus() : CommandBus
    {
        if (is_null($this->command_bus)) {
            $this->command_bus = $this->createCommandBus();
        }

        return $this->command_bus;
    }

    /**
     * @return CommandBus
     */
    private function createCommandBus() : CommandBus
    {
        $command_bus = new CommandBus();

        $command_bus->registerCommand(new CommandConfiguration(
            AddAnswerCommand::class,
            new AddAnswerCommandHandler(),
            new OpenAccess()
        ));

        $command_bus->registerCommand(new CommandConfiguration(
            StartAssessmentCommand::class,
            new StartAssessmentCommandHandler(),
            new OpenAccess()
        ));

        $command_bus->registerCommand(new CommandConfiguration(
            SubmitAssessmentCommand::class,
            new SubmitAssessmentCommandHandler(),
            new OpenAccess()
        ));

        $command_bus->registerCommand(new CommandConfiguration(
            HintReceivedCommand::class,
            new HintReceivedCommandHandler(),
            new OpenAccess()
        ));

        $command_bus->registerCommand(new CommandConfiguration(
            AddScoreCommand::class,
            new AddScoreCommandHandler(),
            new OpenAccess()
        ));

        return $command_bus;
    }

    /**
     * @param AssessmentResultContext $context
     * @param Uuid[] $question_ids
     * @return string
     */
    public function createTestRun(AssessmentResultContext $context, array $question_ids) : Uuid
    {
        $uuid_factory = new Factory();

        $uuid = $uuid_factory->uuid4();

        // CreateQuestion.png
        $this->getCommandBus()->handle(
            new StartAssessmentCommand(
                $uuid,
                $this->getActiveUser(),
                $context,
                $question_ids
            )
        );

        return $uuid;
    }

    /**
     * @param Uuid $uuid
     * @param Uuid $question_id
     * @param AbstractValueObject $answer
     */
    public function addAnswer(Uuid $uuid, Uuid $question_id, AbstractValueObject $answer)
    {
        $this->getCommandBus()->handle(
            new AddAnswerCommand(
                $uuid,
                $this->getActiveUser(),
                $question_id,
                $answer
            )
        );
    }

    /**
     * @param Uuid $uuid
     * @param Uuid $question_id
     * @param QuestionHint $hint
     */
    public function hintRecieved(Uuid $uuid, Uuid $question_id, QuestionHint $hint)
    {
        $this->getCommandBus()->handle(
            new HintReceivedCommand(
                $uuid,
                $this->getActiveUser(),
                $question_id,
                $hint
            )
        );
    }

    /**
     * @param Uuid $uuid
     */
    public function submitTestRun(Uuid $uuid)
    {
        $this->getCommandBus()->handle(
            new SubmitAssessmentCommand(
                $uuid,
                $this->getActiveUser()
            )
        );
    }

    /**
     * @param Uuid $uuid
     */
    public function finishScoring(Uuid $uuid)
    {
        $this->getCommandBus()->handle(
            new FinishScoringCommand(
                $uuid,
                $this->getActiveUser()
            )
        );
    }



    /**
     * @param Uuid $uuid
     * @param Uuid $question_id
     * @param ItemScore $score
     */
    public function addScore(Uuid $uuid, Uuid $question_id, ItemScore $score)
    {
        $this->getCommandBus()->handle(
            new AddScoreCommand(
                $uuid,
                $this->getActiveUser(),
                $question_id,
                $score
            )
        );
    }

    /**
     * @param Uuid $uuid
     * @param Uuid $question_id
     * @return ItemResult|NULL
     */
    public function getItemResult(Uuid $uuid, Uuid $question_id) : ?ItemResult
    {
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById($uuid);

        return $assessment_result->getItemResult($question_id);
    }

    /**
     * @param Uuid $uuid
     * @return string
     */
    public function getFirstQuestionId(Uuid $uuid) : Uuid
    {
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById($uuid);

        return $assessment_result->getQuestions()[0];
    }

    /**
     * @param Uuid $uuid
     * @param Uuid $question_id
     * @return string|NULL
     */
    public function getPreviousQuestionId(Uuid $uuid, Uuid $question_id) : ?Uuid
    {
        $questions = AssessmentResultRepository::getInstance()->getAggregateRootById($uuid)->getQuestions();

        $current_id = array_search($question_id, $questions);

        if ($current_id > 0) {
            return $questions[$current_id - 1];
        } else {
            return null;
        }
    }

    /**
     * @param Uuid $uuid
     * @param Uuid $question_id
     * @return string|NULL
     */
    public function getNextQuestionId(Uuid $uuid, Uuid $question_id) : ?Uuid
    {
        $questions = AssessmentResultRepository::getInstance()->getAggregateRootById($uuid)->getQuestions();

        $current_id = array_search($question_id, $questions);

        if (array_key_exists($current_id + 1, $questions)) {
            return $questions[$current_id + 1];
        } else {
            return null;
        }
    }

    public function getTestResult(string $name)
    {
    }
}
