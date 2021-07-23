<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\Test\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class CreateTestCommand
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CreateTestCommand extends AbstractCommand
{
    /**
     * @var Uuid
     */
    protected $id;

    /**
     * @param Uuid $uuid
     * @param int $user_id
     */
    public function __construct(Uuid $id, int $user_id)
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
}
