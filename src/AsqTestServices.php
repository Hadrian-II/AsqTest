<?php
declare(strict_types=1);

namespace Fluxlabs\Assessment\Test;

use Fluxlabs\Assessment\Test\Application\Section\SectionService;
use Fluxlabs\Assessment\Test\Application\TestRunner\TestRunnerService;
use Fluxlabs\Assessment\Test\Application\Test\TestService;

/**
 * Class AsqTestServices
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AsqTestServices
{
    private static ?AsqTestServices $instance = null;

    private function __construct()
    {
    }

    public static function get() : AsqTestServices
    {
        if (self::$instance === null) {
            self::$instance = new AsqTestServices();
        }

        return self::$instance;
    }

    private ?SectionService $section = null;

    public function section() : SectionService
    {
        if (is_null($this->section)) {
            $this->section = new SectionService();
        }

        return $this->section;
    }

    private  ?TestRunnerService $test_runner = null;

    public function runner() : TestRunnerService
    {
        if (is_null($this->test_runner)) {
            $this->test_runner = new TestRunnerService();
        }

        return $this->test_runner;
    }

    private ?TestService $test = null;

    public function test() : TestService
    {
        if (is_null($this->test)) {
            $this->test = new TestService();
        }

        return $this->test;
    }
}
