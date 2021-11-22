<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use ILIAS\Data\UUID\Uuid;

/**
 * Class RandomQuestionSelectionConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomQuestionSelectionConfiguration extends ObjectConfiguration
{

    protected ?string $source_key;

    protected ?float $points;

    public function __construct(?string $source_key = null, ?float $points = null)
    {
        $this->source_key = $source_key;
        $this->points = $points;
    }

    public function getSourceKey() : string
    {
        return $this->source_key;
    }

    public function getPoints() : ?float
    {
        return $this->points;
    }

    public function moduleName(): string
    {
        return RandomQuestionSelection::class;
    }
}