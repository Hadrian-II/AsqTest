<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Manual;

use Fluxlabs\Assessment\Test\Modules\Questions\Selection\AbstractQuestionSelectionObject;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use ILIAS\Data\UUID\Uuid;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;

/**
 * Class ManualQuestionSelectionObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ManualQuestionSelectionObject extends AbstractQuestionSelectionObject
{
    private ISourceObject $source;

    /**
     * @var Uuid[]
     */
    private array $selected_questions;

    public function __construct(ISourceObject $source, array $selected_questions)
    {
        $this->source = $source;
        $this->selected_questions = $selected_questions;
    }

    public function getSelectedQuestionDefinitions() : array
    {
        return $this->selected_questions;
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
        return new ManualQuestionSelectionConfiguration($this->source->getKey(), $this->selected_questions);
    }
}