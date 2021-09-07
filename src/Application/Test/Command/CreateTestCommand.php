<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class CreateTestCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CreateTestCommand extends AbstractCommand
{
    protected Uuid $id;

    public function __construct(Uuid $id, int $user_id)
    {
        $this->id = $id;
        parent::__construct($user_id);
    }

    public function getId() : Uuid
    {
        return $this->id;
    }
}
