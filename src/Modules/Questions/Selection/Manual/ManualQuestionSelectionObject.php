<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\Manual;

use ILIAS\Data\UUID\Uuid;
use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;

/**
 * Class ManualQuestionSelectionObject
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ManualQuestionSelectionObject implements ISelectionObject
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

    public function getSelectedQuestionIds() : array
    {
        return $this->selected_questions;
    }

    public function getSourceKey(): string
    {
        return $this->source->getKey();
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