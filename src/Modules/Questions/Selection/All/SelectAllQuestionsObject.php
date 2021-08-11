<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\All;

use srag\asq\Test\Domain\Test\Objects\ISelectionObject;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;

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

    public function getKey() : string
    {
        return 'select_all_of_' . $this->source->getKey();
    }

    public function __create(ISourceObject $source)
    {
        $this->source = $source;
    }

    public function getSelectedQuestionIds() : array
    {
        return $this->source->getQuestionIds();
    }
}