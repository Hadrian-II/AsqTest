<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Instance\Model;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AssessmentInstanceRun
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentInstanceRun extends AbstractValueObject
{
    const STATE_OPEN = 1;
    const STATE_SUBMITTED = 2;
    const STATE_CORRECTED = 3;
    const STATE_CANCELLED = 4;
    protected int $state;

    protected int $user_id;

    protected Uuid $result_id;

    public static function create(int $user_id, Uuid $result_id, int $state = self::STATE_OPEN) : AssessmentInstanceRun
    {
        $object = new AssessmentInstanceRun();
        $object->user_id = $user_id;
        $object->result_id = $result_id;
        $object->state = $state;
        return $object;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getResultId(): Uuid
    {
        return $this->result_id;
    }

    public function setState(int $state) :void
    {
        $this->state = $state;
    }
}