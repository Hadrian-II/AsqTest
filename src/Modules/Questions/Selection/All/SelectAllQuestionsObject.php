<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\All;

use Fluxlabs\Assessment\Test\Modules\Questions\Selection\AbstractQuestionSelectionObject;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;

/**
 * Class SelectAllQuestions
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SelectAllQuestionsObject extends AbstractQuestionSelectionObject
{
    private ISourceObject $source;

    public function __construct(ISourceObject $source)
    {
        $this->source = $source;
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
        return new SelectAllQuestionsConfiguration($this->source->getKey());
    }
}