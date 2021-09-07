<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Section\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class StartAssessmentCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class CreateSectionCommand extends AbstractCommand
{
    protected Uuid $uuid;

    public function __construct(Uuid $uuid, int $user_id)
    {
        $this->uuid = $uuid;
        parent::__construct($user_id);
    }

    public function getId() : Uuid
    {
        return $this->uuid;
    }
}
