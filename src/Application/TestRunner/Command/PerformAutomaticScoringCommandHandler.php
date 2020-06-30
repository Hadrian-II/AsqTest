<?php

namespace srag\asq\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use srag\asq\Test\Domain\Result\Model\AssessmentResult;
use srag\asq\Test\Domain\Result\Model\AssessmentResultRepository;
use ILIAS\Data\Result\Ok;
use srag\asq\AsqGateway;
use srag\asq\Test\Domain\Result\Model\ItemScore;

/**
 * Class PerformAutomaticScoringCommandHandler
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class PerformAutomaticScoringCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command PerformAutomaticScoringCommand
     */
    public function handle(CommandContract $command) : Result
    {
        /** @var $assessment_result AssessmentResult */
        $assessment_result = AssessmentResultRepository::getInstance()->getAggregateRootById($command->getResultUuid());

        foreach ($assessment_result->getQuestions() as $question_id) {
            $question = AsqGateway::get()->question()->getQuestionByQuestionId($question_id);
            $result = $assessment_result->getItemResult($question_id);

            $reached_score = 0.0;
            if (!is_null($result->getAnswer())) {
                $reached_score = AsqGateway::get()->answer()->getScore($question, $result->getScore());
            }

            $score = ItemScore::create(
                ItemScore::AUTOMATIC_SCORING,
                AsqGateway::get()->answer()->getMaxScore($question),
                AsqGateway::get()->answer()->getMinScore($question),
                $reached_score
            );

            $assessment_result->setScore($question_id, $score, $command->getIssuingUserId());
        }

        $assessment_result->finishScoring($command->getIssuingUserId());

        AssessmentResultRepository::getInstance()->save($assessment_result);

        return new Ok(null);
    }
}
