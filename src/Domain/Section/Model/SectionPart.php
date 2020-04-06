<?php

namespace srag\asq\Test\Domain\Section\Model;


use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class SectionPart
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class SectionPart extends AbstractValueObject {  
    const TYPE_QUESTION = 1;
    const TYPE_SECTION = 2;
    
    /**
     * @var string
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
     * @param string $id
     * @param string $revision_name
     * @param int $type
     * @return SectionPart
     */
    public static function create(int $type, string $id, ?string $revision_name = null): SectionPart {
        $object = new SectionPart();
        $object->id = $id;
        $object->revision_name = $revision_name;
        $object->type = $type;
        return $object;
    }
    
    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }
    
    /**
     * @return ?string
     */
    public function getRevisionName(): ?string {
        return $this->revision_name;
    }
    
    /**
     * @return int
     */
    public function getType(): int {
        return $this->type;
    }
    
    /**
     * @return string
     */
    public function getKey(): string {
        return sprintf('%s_%s_%s', $this->type, $this->id, $this->revision_name);
    }
}