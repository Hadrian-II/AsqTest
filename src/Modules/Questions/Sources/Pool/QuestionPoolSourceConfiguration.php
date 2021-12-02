<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use ILIAS\Data\UUID\Uuid;

/**
 * Class QuestionPoolSourceConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSourceConfiguration extends ObjectConfiguration
{
    protected ?Uuid $uuid;


    /**
     * @var QuestionDefinition[]
     */
    protected ?array $questions;

    public function __construct(?Uuid $uuid = null, ?array $questions = null)
    {
        $this->uuid = $uuid;
        $this->questions = $questions;
    }

    public function getUuid() : ?Uuid
    {
        return $this->uuid;
    }

    public function moduleName(): string
    {
        return QuestionPoolSource::class;
    }

    public function getQuestions() : array
    {
        return $this->questions ?? [];
    }
}