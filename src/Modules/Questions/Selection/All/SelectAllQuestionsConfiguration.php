<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\All;

use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;

/**
 * Class SelectAllQuestionsConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SelectAllQuestionsConfiguration extends ObjectConfiguration
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
        return SelectAllQuestions::class;
    }
}