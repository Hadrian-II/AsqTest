<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Model;

use srag\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;
use ILIAS\Data\UUID\Factory;

/**
 * Class SectionPart
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SectionPart extends AbstractValueObject
{
    const TYPE_QUESTION = 1;
    const TYPE_SECTION = 2;

    protected ?Uuid $id;

    protected ?string $revision_name;

    protected ?int $type;

    public function __construct(int $type = null, Uuid $id = null, ?string $revision_name = null)
    {
        $this->id = $id;
        $this->revision_name = $revision_name;
        $this->type = $type;
    }

    public function getId() : Uuid
    {
        return $this->id;
    }

    public function getRevisionName() : ?string
    {
        return $this->revision_name;
    }

    public function getType() : int
    {
        return $this->type;
    }

    public function getKey() : string
    {
        return sprintf('%s_%s_%s', $this->type, $this->id->toString(), $this->revision_name);
    }
}
