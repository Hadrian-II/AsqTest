<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Modules;

/**
 * Interface IQuestionModule
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IAvailabilityModule
{
    /**
     * @return bool
     */
    public function isAvailable() : bool;
}