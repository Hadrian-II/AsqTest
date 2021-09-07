<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\Fixed;

use Fluxlabs\Assessment\Test\Application\Test\Module\AbstractTestModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\ITestModule;
use Fluxlabs\Assessment\Test\Application\Test\Module\IQuestionSourceModule;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\AbstractQuestionSource;

/**
 * Class FixedSource
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class FixedSource extends AbstractQuestionSource
{
    const CREATE_STATIC_SOURCE = 'sqsCreate';

    public function getQuestions(): array
    {

    }

    public function getCommands() : array
    {
        return [
            self::CREATE_STATIC_SOURCE
        ];
    }

    public function getInitializationCommand(): string
    {
        return self::CREATE_STATIC_SOURCE;
    }

    protected function sqsCreate() : string
    {
        return 'FIXED SOURCE';
    }
}