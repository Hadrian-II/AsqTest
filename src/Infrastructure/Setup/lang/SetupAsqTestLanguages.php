<?php
declare(strict_types=1);

namespace Fluxlabs\Assessment\Test\Infrastructure\Setup\lang;

use srag\asq\Infrastructure\Setup\lang\SetupLanguages;

/**
 * Class SetupAsqTestLanguages
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class SetupAsqTestLanguages extends SetupLanguages
{
    const ASQ_TEST_LANGUAGE_PREFIX = 'asqt';

    public function getLanguagePrefix() : string
    {
        return self::ASQ_TEST_LANGUAGE_PREFIX;
    }
}
