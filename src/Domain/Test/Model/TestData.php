<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Test\Model;

use srag\CQRS\Aggregate\AbstractValueObject;
use Fluxlabs\Assessment\Test\Domain\Test\IAsqPlugin;
use Fluxlabs\Assessment\Test\Leipzig\LeipzigTest;

/**
 * Class TestDefinition
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TestData extends AbstractValueObject
{
    protected ?string $title;

    protected ?string $description;

    public function __construct(?string $title = null, ?string $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }
}