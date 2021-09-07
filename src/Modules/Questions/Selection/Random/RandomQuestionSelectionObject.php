<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;

/**
 * Class RandomQuestionSelectionObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomQuestionSelectionObject implements ISelectionObject
{
    private ISourceObject $source;

    public function __construct(ISourceObject $source)
    {
        $this->source = $source;
    }

    public function getSelectedQuestionIds() : array
    {
        return $this->source->getQuestionIds();
    }

    public function getSource(): ISourceObject
    {
        return $this->source;
    }

    public function getKey() : string
    {
        return 'select_all_of_' . $this->source->getKey();
    }

    public function getConfiguration(): ObjectConfiguration
    {
        return new RandomQuestionSelectionConfiguration($this->source->getKey());
    }
}