<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use srag\asq\Test\Domain\Test\Objects\ObjectConfiguration;

/**
 * Class QuestionPoolSourceConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSourceConfiguration extends ObjectConfiguration
{
    protected ?Uuid $uuid;

    public function __construct(?Uuid $uuid = null)
    {
        $this->uuid = $uuid;
    }

    public function getUuid() : ?Uuid
    {
        return $this->uuid;
    }

    public function moduleName(): string
    {
        return QuestionPoolSource::class;
    }

    protected static function deserializeValue(string $key, $value)
    {
        if ($key === 'uuid') {
            $factory = new Factory();
            return $factory->fromString($value);
        }

        return $value;
    }
}