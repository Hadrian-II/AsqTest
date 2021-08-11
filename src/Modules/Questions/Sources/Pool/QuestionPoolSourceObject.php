<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use ILIAS\Data\UUID\Uuid;
use srag\asq\QuestionPool\Application\QuestionPoolService;
use srag\asq\Test\Domain\Test\Objects\ISourceObject;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;

/**
 * Class QuestionPoolSourceObject
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSourceObject implements ISourceObject
{
    private QuestionPoolService $pool_service;

    private Uuid $uuid;

    public function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
        $this->pool_service = new QuestionPoolService();
    }

    public function getQuestionIds(): array
    {
        return $this->pool_service->getQuestionsOfPool($this->uuid);
    }

    public function getKey(): string
    {
        return 'pool_source_for_pool' . $this->uuid;
    }

    public function getConfiguration(): ObjectConfiguration
    {
        return new QuestionPoolSourceConfiguration($this->uuid);
    }
}