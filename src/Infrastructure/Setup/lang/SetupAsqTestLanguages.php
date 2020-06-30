<?php
declare(strict_types=1);

namespace srag\asq\Test\Infrastructure\Setup\lang;

use srag\asq\Infrastructure\Setup\lang\SetupLanguages;

/**
 * Class SetupAsqTestLanguages
 *
 * @author Adrian Lüthi <al@studer-raimann.ch>
 */
class SetupAsqTestLanguages extends SetupLanguages
{
    public function getLanguagePrefix() : string
    {
        return "asqt";
    }
}
