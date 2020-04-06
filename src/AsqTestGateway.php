<?php
declare(strict_types=1);

namespace srag\asq\Test;

use srag\asq\Test\Application\Section\SectionService;
use srag\asq\Test\Application\TestRunner\TestRunnerService;

/**
 * Class AsqGateway
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq/Test
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class AsqTestGateway
{
    /**
     * @var AsqTestGateway
     */
    private static $instance;
    
    private function __construct() { }

    /**
     * @return AsqTestGateway
     */
    public static function get() : AsqTestGateway {
        if (is_null(self::$instance)) {
            self::$instance = new AsqTestGateway();
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
    public function section(): SectionService {
        if(is_null($this->section)) {
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
    public function runner(): TestRunnerService {
        if (is_null($this->test_runner)) {
            $this->test_runner = new TestRunnerService();
        }
        
        return $this->test_runner;
    }
}