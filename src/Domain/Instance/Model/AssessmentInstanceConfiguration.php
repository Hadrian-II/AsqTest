<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Model;

use DateTimeImmutable;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentInstanceConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentInstanceConfiguration extends AbstractValueObject
{
    protected Uuid $test_id;

    protected DateTimeImmutable $start_time;

    protected DateTimeImmutable $end_time;

    protected int $tries;
    const UNLIMITED_TRIES = -1;

    public static function create(
        Uuid $test_id,
        DateTimeImmutable $start_time,
        DateTimeImmutable $end_time ,
        int $tries = self::UNLIMITED_TRIES) : AssessmentInstanceConfiguration
    {
        $object = new AssessmentInstanceConfiguration();
        $object->test_id = $test_id;
        $object->start_time = $start_time;
        $object->end_time = $end_time;
        $object->tries = $tries;
        return $object;
    }

    public function getTestId(): Uuid
    {
        return $this->test_id;
    }

    public function getStartTime(): DateTimeImmutable
    {
        return $this->start_time;
    }

    public function getEndTime(): DateTimeImmutable
    {
        return $this->end_time;
    }

    public function getTries(): int
    {
        return $this->tries;
    }
}