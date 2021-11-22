<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

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

    private Uuid $question_id;

    public function __construct(float $points, Uuid $question_id)
    {
        $this->points = $points;

        $this->question_id = $question_id;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function getQuestionId(): Uuid
    {
        return $this->question_id;
    }
}