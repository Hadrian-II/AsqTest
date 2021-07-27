<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Section\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;
use srag\asq\Test\Domain\Section\Model\SectionPart;

/**
 * Class AddItemCommand
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AddItemCommand extends AbstractCommand
{
    public Uuid $section_id;

    public SectionPart $item;

    public function __construct(Uuid $section_id, int $user_id, SectionPart $item)
    {
        $this->section_id = $section_id;
        $this->item = $item;
        parent::__construct($user_id);
    }

    public function getSectionId() : Uuid
    {
        return $this->section_id;
    }

    public function getItem() : SectionPart
    {
        return $this->item;
    }
}
