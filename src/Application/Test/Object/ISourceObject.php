<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Object;

use Fluxlabs\Assessment\Test\Modules\Storage\AssessmentTestObject\Event\QuestionDefinition;
use ILIAS\Data\UUID\Uuid;

/**
 * Interface ISourceObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface ISourceObject extends IQuestionObject
{
    /**
     * @return QuestionDefinition[]
     */
    public function getQuestions() : array;

    /**
     * @return Uuid[]
     */
    public function getAllQuestions() : array;

    /**
     * @param QuestionDefinition[] $selections
     */
    public function setSelections(array $selections) : void;
}