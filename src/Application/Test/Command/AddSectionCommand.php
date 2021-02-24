<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Test\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class AddSectionCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AddSectionCommand extends AbstractCommand
{
    /**
     * @var Uuid
     */
    protected $id;

    /**
     * @var Uuid
     */
    protected $section_id;

    /**
     * @param Uuid $uuid
     * @param int $user_id
     */
    public function __construct(Uuid $id, Uuid $section_id, int $user_id)
    {
        $this->id = $id;
        parent::__construct($user_id);
    }

    /**
     * @return Uuid
     */
    public function getId() : Uuid
    {
        return $this->id;
    }

    /**
     * @return Uuid
     */
    public function getSectionId() : Uuid
    {
        return $this->section_id;
    }
}
