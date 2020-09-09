<?php

namespace srag\asq\Test\Application\Section\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class StartAssessmentCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class CreateSectionCommand extends AbstractCommand
{
    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @param Uuid $uuid
     * @param int $user_id
     */
    public function __construct(Uuid $uuid, int $user_id)
    {
        $this->uuid = $uuid;
        parent::__construct($user_id);
    }

    /**
     * @return Uuid
     */
    public function getId() : Uuid
    {
        return $this->uuid;
    }
}
