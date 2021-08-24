<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection\Manual;

use ILIAS\Data\UUID\Uuid;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;
use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class SelectAllQuestionsConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ManualQuestionSelectionConfiguration extends ObjectConfiguration
{
    protected ?string $source_key;

    /**
     * @var ?Uuid[]
     */
    protected ?array $selected_questions;

    public function __construct(?string $source_key = null, ?array $selected_questions = null)
    {
        $this->source_key = $source_key;
        $this->selected_questions = $selected_questions;
    }

    public function getSourceKey() : string
    {
        return $this->source_key;
    }

    public function getSelectedQuestions() : array
    {
        return $this->selected_questions ?? [];
    }

    public function moduleName(): string
    {
        return ManualQuestionSelection::class;
    }
}