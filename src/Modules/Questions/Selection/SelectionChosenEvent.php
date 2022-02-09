<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection;

use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;
use Fluxlabs\Assessment\Tools\Event\Event;
use Fluxlabs\Assessment\Tools\Event\IEventUser;

/**
 * Class SelectionChosenEvent
 *
 * @package Fluxlabs\Assessment\Tools
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SelectionChosenEvent extends Event
{
    private ISourceObject $source;

    private ISelectionObject $selection;

    public function __construct(IEventUser $sender, ISourceObject $source, ISelectionObject $selection)
    {
        $this->source = $source;
        $this->selection = $selection;

        parent::__construct($sender);
    }

    public function getSource(): ISourceObject
    {
        return $this->source;
    }

    public function getSelection(): ISelectionObject
    {
        return $this->selection;
    }
}