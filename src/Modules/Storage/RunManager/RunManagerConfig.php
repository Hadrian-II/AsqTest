<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\RunManager;

use DateTimeImmutable;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class RunManagerConfig
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RunManagerConfig extends AbstractValueObject
{
    protected ?DateTimeImmutable $start = null;

    protected ?DateTimeImmutable $end = null;

    public static function create(DateTimeImmutable $start, DateTimeImmutable $end) : RunManagerConfig
    {
        $object = new RunManagerConfig();
        $object->start = $start;
        $object->end = $end;
        return $object;
    }

    public function getStart() : ?DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd() : ?DateTimeImmutable
    {
        return $this->end;
    }
}