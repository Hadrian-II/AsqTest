<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Section\Command;

use ILIAS\Data\UUID\Uuid;
use Fluxlabs\CQRS\Command\AbstractCommand;

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

    public function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
        parent::__construct();
    }

    public function getId() : Uuid
    {
        return $this->uuid;
    }
}
