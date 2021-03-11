<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI;

use srag\asq\Test\Domain\Test\ITest;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;

/**
 * Class AsqTestServices
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq/Test
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class ConfigurationGUI
{
    /**
     * @var string
     */
    const CURRENT_CONFIG = 'CurrentConfig';

    /**
     * @var AssessmentTestDto
     */
    private $test;

    /**
     * @var ITest
     */
    private $test_module;

    /**
     * @param AssessmentTestDto $test
     */
    public function __construct(AssessmentTestDto $test)
    {
        $this->test = $test;
        $this->test_module = $test->getTestData()->getTest();
    }

    /**
     * @return array
     */
    public function getSubTabs() : array {
        $subtabs = [];

        foreach ($this->test_module->getModules() as $module) {
            $type = $module->getType();
            if ($type !== null && !array_key_exists($type, $subtabs)) {
                $subtabs[$type] = 'translated_' . $type;
            }
        }

        return $subtabs;
    }

    public function save() : void
    {

    }

    /**
     * @param string $module
     * @return string
     */
    public function render(string $module) : string
    {
        return 'CONFIG';
    }
}