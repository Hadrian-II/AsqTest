<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\All;

use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;

/**
 * Class SelectAllQuestions
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SelectAllQuestionsObject implements ISelectionObject
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
        return new SelectAllQuestionsConfiguration($this->source->getKey());
    }
}