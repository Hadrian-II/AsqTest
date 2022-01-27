<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\Test\Module;

use Fluxlabs\Assessment\Tools\Domain\Modules\IAsqModule;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISelectionObject;
use Fluxlabs\Assessment\Tools\Domain\Objects\IAsqObject;

/**
 * Interface IQuestionModule
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
interface IQuestionModule extends IAsqModule
{
    /**
     * Gets the command that is executed to create a new QuestionSource
     *
     * @return string
     */
    public function getInitializationCommand() : string;

    /**
     * Gets Actions that can be performed on the question Page
     *
     * @param IAsqObject $object
     * @return string
     */
    public function getQuestionPageActions(IAsqObject $object) : string;

    /**
     * Get title key of module to provide translation
     *
     * @return string
     */
    public function getTitleKey() : string;
}