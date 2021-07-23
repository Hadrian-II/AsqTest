<?php
declare(strict_types=1);

namespace srag\asq\Test\Infrastructure\Setup\lang;

use srag\asq\Infrastructure\Setup\lang\SetupLanguages;

/**
 * Class SetupAsqTestLanguages
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SetupAsqTestLanguages extends SetupLanguages
{
    public function getLanguagePrefix() : string
    {
        return "asqt";
    }
}
