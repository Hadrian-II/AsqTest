<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\QuestionDisplay;

use Fluxlabs\Assessment\Tools\Domain\Modules\AbstractAsqModule;
use srag\asq\Domain\QuestionDto;
use Fluxlabs\Assessment\Test\Application\Test\Module\IPlayerModule;

/**
 * Class QuestionDisplay
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDisplay extends AbstractAsqModule implements IPlayerModule
{
    public function getConfigClass() : ?string
    {
        return QuestionDisplayConfigurationFactory::class;
    }

    public function getNextQuestion(): ?QuestionDto
    {

    }

    public function getFirstQuestion(): QuestionDto
    {

    }

    public function getPreviousQuestion(): ?QuestionDto
    {

    }
}