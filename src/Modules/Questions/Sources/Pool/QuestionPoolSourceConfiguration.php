<?php


namespace srag\asq\Test\Modules\Questions\Sources\Pool;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionPoolSourceConfiguration
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs ag - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionPoolSourceConfiguration extends AbstractValueObject
{
    private ?string $uuid;

    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid;
    }

    public function getUuid() : string
    {
        return $this->uuid;
    }
}