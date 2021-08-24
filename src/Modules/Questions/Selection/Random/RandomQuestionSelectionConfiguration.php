<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\Random;

use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class RandomQuestionSelectionConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomQuestionSelectionConfiguration extends ObjectConfiguration
{
    protected ?string $source_key;

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
        return RandomQuestionSelection::class;
    }
}