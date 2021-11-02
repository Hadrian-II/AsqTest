<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Command;

use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Command\AbstractCommand;

/**
 * Class RemoveSectionCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RemoveSectionCommand extends AbstractCommand
{
    protected Uuid $id;

    protected Uuid $section_id;

    public function __construct(Uuid $id, Uuid $section_id)
    {
        $this->id = $id;
        $this->section_id = $section_id;
        parent::__construct();
    }

    public function getId() : Uuid
    {
        return $this->id;
    }

    public function getSectionId() : Uuid
    {
        return $this->section_id;
    }
}
