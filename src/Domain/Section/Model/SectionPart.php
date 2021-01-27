<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Section\Model;

use srag\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;
use ILIAS\Data\UUID\Factory;

/**
 * Class SectionPart
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class SectionPart extends AbstractValueObject
{
    const TYPE_QUESTION = 1;
    const TYPE_SECTION = 2;

    /**
     * @var Uuid
     */
    protected $id;

    /**
     * @var ?string
     */
    protected $revision_name;

    /**
     * @var int
     */
    protected $type;

    /**
     * @param int $type
     * @param Uuid $id
     * @param string $revision_name
     */
    public function __construct(int $type = null, Uuid $id = null, ?string $revision_name = null)
    {
        $this->id = $id;
        $this->revision_name = $revision_name;
        $this->type = $type;
    }

    /**
     * @return Uuid
     */
    public function getId() : Uuid
    {
        return $this->id;
    }

    /**
     * @return ?string
     */
    public function getRevisionName() : ?string
    {
        return $this->revision_name;
    }

    /**
     * @return int
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return sprintf('%s_%s_%s', $this->type, $this->id->toString(), $this->revision_name);
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return \ILIAS\Data\UUID\Uuid|mixed
     */
    protected static function deserializeValue(string $key, $value)
    {
        if ($key === 'id') {
            $factory = new Factory();
            return $factory->fromString($value);
        }
        //virtual method
        return $value;
    }
}
