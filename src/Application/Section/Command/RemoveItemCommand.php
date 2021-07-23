<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Section\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;
use srag\asq\Test\Domain\Section\Model\SectionPart;

/**
 * Class RemoveItemCommand
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RemoveItemCommand extends AbstractCommand
{
    /**
     * @var Uuid
     */
    public $section_id;

    /**
     * @var SectionPart
     */
    public $item;

    /**
     * @param Uuid $section_id
     * @param int $user_id
     * @param SectionPart $item
     */
    public function __construct(Uuid $section_id, int $user_id, SectionPart $item)
    {
        $this->section_id = $section_id;
        $this->item = $item;
        parent::__construct($user_id);
    }

    /**
     * @return Uuid
     */
    public function getSectionId() : Uuid
    {
        return $this->section_id;
    }

    /**
     * @return SectionPart
     */
    public function getItem() : SectionPart
    {
        return $this->item;
    }
}
