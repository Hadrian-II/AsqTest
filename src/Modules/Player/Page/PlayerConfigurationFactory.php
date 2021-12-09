<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page;


use Fluxlabs\Assessment\Test\Modules\Player\Page\QuestionDisplay\QuestionDisplayConfigurationFactory;
use Fluxlabs\Assessment\Tools\Domain\Model\Configuration\CompoundConfigurationFactory;
use ILIAS\DI\UIServices;
use ilLanguage;
use srag\asq\Application\Service\UIService;

/**
 * Class PlayerConfigurationFactory
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class PlayerConfigurationFactory extends CompoundConfigurationFactory
{
    public function __construct(ilLanguage $language, UIServices $ui, UIService $asq_ui)
    {
        $this->addFactory(new QuestionDisplayConfigurationFactory($language, $ui, $asq_ui));
    }
}