<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class RandomSelectionQuestion
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomSelectionQuestion extends AbstractValueObject
{
    private float $points;

    private QuestionDefinition $question_definition;

    public function __construct(float $points, QuestionDefinition $question_definition)
    {
        $this->points = $points;

        $this->question_definition = $question_definition;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function getQuestionDefinition(): QuestionDefinition
    {
        return $this->question_definition;
    }
}