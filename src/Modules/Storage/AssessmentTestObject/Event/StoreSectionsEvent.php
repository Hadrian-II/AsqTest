<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event;

use Fluxlabs\Assessment\Tools\Event\Event;
use Fluxlabs\Assessment\Tools\Event\IEventUser;

/**
 * Class StoreSectionEvent
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class StoreSectionsEvent extends Event
{
    /**
     * @var SectionDefinition[]
     */
    protected array $sections;

    public function __construct(IEventUser $sender, array $sections)
    {
        parent::__construct($sender);

        $this->sections = $sections;
    }
    /**
     * @return SectionDefinition[]
     */
    public function getSections() : array
    {
        return $this->sections;
    }
}