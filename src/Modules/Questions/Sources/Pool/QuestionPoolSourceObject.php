<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\Assessment\Test\Modules\Questions\AbstractQuestionObject;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use ILIAS\Data\UUID\Uuid;
use srag\asq\QuestionPool\Application\QuestionPoolService;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;

/**
 * Class QuestionPoolSourceObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSourceObject extends AbstractQuestionObject implements ISourceObject
{
    private QuestionPoolService $pool_service;

    private Uuid $uuid;

    /**
     * @var QuestionDefinition[]
     */
    private ?array $questions;

    public function __construct(Uuid $uuid, ?array $questions)
    {
        $this->uuid = $uuid;
        $this->questions = $questions;
        $this->pool_service = new QuestionPoolService();
    }

    public function getQuestions(): array
    {
        return $this->questions;
    }

    public function setSelections(array $selections): void
    {
        $this->questions = $selections;
    }

    public function getAllQuestions(): array
    {
        return $this->pool_service->getQuestionsOfPool($this->uuid);
    }

    public function getKey(): string
    {
        return 'pool_source_for_pool' . $this->uuid->toString();
    }

    public function getConfiguration(): ObjectConfiguration
    {
        return new QuestionPoolSourceConfiguration($this->uuid);
    }
}