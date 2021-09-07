<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Ok;
use srag\CQRS\Command\CommandContract;
use srag\CQRS\Command\CommandHandlerContract;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultRepository;
use Fluxlabs\Assessment\Test\Domain\Result\Model\ItemScore;

/**
 * Class PerformAutomaticScoringCommandHandler
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class PerformAutomaticScoringCommandHandler implements CommandHandlerContract
{
    /**
     * @param $command PerformAutomaticScoringCommand
     */
    public function handle(CommandContract $command) : Result
    {
        global $ASQDIC;

        $repo = new AssessmentResultRepository();

        $assessment_result = $repo->getAggregateRootById($command->getResultUuid());

        foreach ($assessment_result->getQuestions() as $question_id) {
            $question = $ASQDIC->asq()->question()->getQuestionByQuestionId($question_id);
            $result = $assessment_result->getItemResult($question_id);

            $reached_score = 0.0;
            if (!is_null($result->getAnswer())) {
                $reached_score = $ASQDIC->asq()->answer()->getScore($question, $result->getScore());
            }

            $score = new ItemScore(
                ItemScore::AUTOMATIC_SCORING,
                $ASQDIC->asq()->answer()->getMaxScore($question),
                $ASQDIC->asq()->answer()->getMinScore($question),
                $reached_score
            );

            $assessment_result->setScore($question_id, $score, $command->getIssuingUserId());
        }

        $assessment_result->finishScoring($command->getIssuingUserId());

        $repo->save($assessment_result);

        return new Ok(null);
    }
}
