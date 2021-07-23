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
    /**
     * @var AsqTestServices
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return AsqTestServices
     */
    public static function get() : AsqTestServices
    {
        if (is_null(self::$instance)) {
            self::$instance = new AsqTestServices();
        }

        return self::$instance;
    }

    /**
     * @var SectionService
     */
    private $section;

    /**
     * @return SectionService
     */
    public function section() : SectionService
    {
        if (is_null($this->section)) {
            $this->section = new SectionService();
        }

        return $this->section;
    }

    /**
     * @var TestRunnerService
     */
    private $test_runner;

    /**
     * @return TestRunnerService
     */
    public function runner() : TestRunnerService
    {
        if (is_null($this->test_runner)) {
            $this->test_runner = new TestRunnerService();
        }

        return $this->test_runner;
    }

    /**
     * @var TestService
     */
    private $test;

    public function test() : TestService
    {
        if (is_null($this->test)) {
            $this->test = new TestService();
        }

        return $this->test;
    }
}
