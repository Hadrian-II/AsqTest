<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Section\Command;

use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Command\AbstractCommand;
use Fluxlabs\Assessment\Test\Domain\Section\Model\SectionPart;

/**
 * Class RemoveItemCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RemoveItemCommand extends AbstractCommand
{
    public Uuid $section_id;

    public SectionPart $item;

    public function __construct(Uuid $section_id, SectionPart $item)
    {
        $this->section_id = $section_id;
        $this->item = $item;
        parent::__construct();
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
