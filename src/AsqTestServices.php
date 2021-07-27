<?php
declare(strict_types=1);

namespace srag\asq\Test;

use srag\asq\Test\Application\Section\SectionService;
use srag\asq\Test\Application\TestRunner\TestRunnerService;
use srag\asq\Test\Application\Test\TestService;

/**
 * Class AsqTestServices
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AsqTestServices
{
    private static AsqTestServices $instance;

    private function __construct()
    {
    }

    public static function get() : AsqTestServices
    {
        if (is_null(self::$instance)) {
            self::$instance = new AsqTestServices();
        }

        return self::$instance;
    }

    private SectionService $section;

    public function section() : SectionService
    {
        if (is_null($this->section)) {
            $this->section = new SectionService();
        }

        return $this->section;
    }

    private  TestRunnerService$test_runner;

    public function runner() : TestRunnerService
    {
        if (is_null($this->test_runner)) {
            $this->test_runner = new TestRunnerService();
        }

        return $this->test_runner;
    }

    private TestService $test;

    public function test() : TestService
    {
        if (is_null($this->test)) {
            $this->test = new TestService();
        }

        return $this->test;
    }
}
