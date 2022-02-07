<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

use ILIAS\Data\UUID\Uuid;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Application\Service\AsqServices;

/**
 * Class RandomQuestionSelection
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomSelectionProcessor extends AbstractValueObject
{
    private float $points;

    /**
     * @var RandomSelectionQuestion[]
     */
    private array $questions;

    public function __construct(float $points, array $questions)
    {
        global $ASQDIC;
        /** @var AsqServices $services */
        $services = $ASQDIC->asq();

        $this->points = $points;

        $this->questions = array_map(function(QuestionDefinition $question_definition) use ($services) {
            $question = $services->question()->getQuestionByQuestionId(
                $question_definition->getQuestionId(),
                $question_definition->getRevisionName()
            );
            $points = $services->answer()->getMaxScore($question);
            return new RandomSelectionQuestion($points, $question_definition);
        }, $questions);
    }

    public function selectionPossible() : bool
    {
        $possible_points = [];

        foreach ($this->questions as $question) {
            foreach (array_keys($possible_points) as $reached_points) {
                $possible_points[strval($question->getPoints() + floatval($reached_points))] = true;
            }

            $possible_points[$question->getPoints()] = true;
        }

        return array_key_exists(strval($this->points), $possible_points);
    }

    /**
     * @return Uuid[]
     * @throws AsqException
     */
    public function selectQuestions() : array
    {
        $question_pool = $this->questions;
        shuffle($question_pool);
        $selected_questions = [];

        foreach ($question_pool as $question) {
            // check if it is possible to exit with only selecting the current question
            if ($question->getPoints() === $this->points) {
                return $this->getDefinitions([$question]);
            }

            // if it is possible to reach wanted points with current question return
            $fits = strval($this->points - $question->getPoints());
            if (array_key_exists($fits, $selected_questions)) {
                return $this->getDefinitions(array_merge($selected_questions[$fits], [$question]));
            }

            // if this question added to already existing combinations gives a new point total,
            // add to possibilities if still below wanted points
            foreach (array_keys($selected_questions) as $current_points) {
                $sum = floatval($current_points) + $question->getPoints();

                if ($sum < $this->points &&
                    !array_key_exists(strval($sum), $selected_questions))
                {
                    $selected_questions[strval($sum)] =
                        array_merge($selected_questions[$current_points],[$question]);
                }
            }

            // if this question alone gives a new point total, add to possibilities
            if (!array_key_exists(strval($question->getPoints()), $selected_questions)) {
                $selected_questions[strval($question->getPoints())] = [$question];
            }
        }

        throw new AsqException("Question Selection impossible, please use validate() before trying to use Randomselection");
    }

    /**
     * @param RandomSelectionQuestion[] $questions
     * @return QuestionDefinition[]
     */
    private function getDefinitions(array $questions) : array
    {
        return array_map(function($question) {
            return $question->getQuestionDefinition();
        }, $questions);
    }
}