<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\All;

use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class SelectAllQuestionsConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SelectAllQuestionsConfiguration extends ObjectConfiguration
{
    private ?string $source_key;

    public function __construct(?string $source_key = null)
    {
        $this->source_key = $source_key;
    }

    public function getSourceKey() : string
    {
        return $this->source_key;
    }

    public function moduleName(): string
    {
        return SelectAllQuestions::class;
    }
}