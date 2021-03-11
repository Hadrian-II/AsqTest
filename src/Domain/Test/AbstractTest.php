<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test;

use ILIAS\Data\Result;

/**
 * Interface Test
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
abstract class AbstractTest implements ITest
{
    public function onBeforeEvent(): Result
    {
        return null;
    }

    public function onPostEvent(): Result
    {
        return null;
    }

    public function onEvent(): Result
    {
        return null;
    }
}