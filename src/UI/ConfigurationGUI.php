<?php
declare(strict_types = 1);

namespace srag\asq\Test\UI;

use srag\asq\Application\Service\UIService;
use srag\asq\Test\Domain\Test\ITest;
use srag\asq\Test\Domain\Test\Model\AssessmentTestDto;
use srag\CQRS\Aggregate\AbstractValueObject;
use ILIAS\UI\Component\Input\Container\Form\Form;
use ILIAS\DI\UIServices;
use srag\asq\UserInterface\Web\Form\Factory\IObjectFactory;
use ilLanguage;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AsqTestServices
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class ConfigurationGUI
{
    /**
     * @var string
     */
    const CURRENT_CONFIG = 'CurrentConfig';

    /**
     * @var AbstractValueObject
     */
    private $current_data;

    /**
     * @var AssessmentTestDto
     */
    private $test;

    /**
     * @var ITest
     */
    private $test_module;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var IObjectFactory[]
     */
    private $factories;

    /**
     * @var UIServices
     */
    private $ui;

    /**
     * @var UIService
     */
    private $asq_ui;

    /**
     * @var ilLanguage
     */
    private $language;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @param AssessmentTestDto $test
     */
    public function __construct(AssessmentTestDto $test)
    {
        global $DIC, $ASQDIC;
        $this->ui = $DIC->ui();
        $this->language = $DIC->language();
        $this->asq_ui = $ASQDIC->asq()->ui();
        $this->request = $DIC->http()->request();

        $this->test = $test;
        $this->test_module = $test->getTestData()->getTest();
        $current = $_GET[self::CURRENT_CONFIG] ?? reset($this->test_module->getModules())->getType();
        $this->factories = $this->getCurrentFactories($current);
        $this->initiateForm();
    }

    /**
     * @param string $current
     * @return array
     */
    private function getCurrentFactories(string $current) : array
    {
        $modules =
        array_filter(
            $this->test_module->getModules(),
            function($module) use($current)
            {
                return $module->getType() === $current;
        });

        $factories = [];
        foreach ($modules as $module) {
            $config_class = $module->getConfigClass();

            if ($config_class === null) {
                continue;
            }

            $factories[get_class($module)] = new $config_class($this->language, $this->ui, $this->asq_ui);
        }
        return $factories;
    }

    private function initiateForm() : void
    {
        $sections = [];

        foreach ($this->factories as $module => $factory) {
            $sections[] = $this->ui->factory()->input()->field()->section(
                $factory->getFormfields($this->test->getConfiguration($module)),
                $module
            );
        }

        $this->form = $this->ui->factory()->input()->container()->form()->standard('', $sections);
    }

    /**
     * @return array
     */
    public function getSubTabs() : array
    {
        $subtabs = [];

        foreach ($this->test_module->getModules() as $module) {
            $type = $module->getType();
            if ($type !== null && !array_key_exists($type, $subtabs)) {
                $subtabs[$type] = 'translated_' . $type;
            }
        }

        return $subtabs;
    }

    public function getEditedTest() : AssessmentTestDto
    {
        $this->form = $this->form->withRequest($this->request);

        $postdata = array_reduce($this->form->getData(), function($all_values, $section_values) {
            return array_merge($all_values, $section_values);
        }, []);

        foreach ($this->factories as $module => $factory) {
            $data = $factory->readObjectFromPost($postdata);
            $this->test->setConfiguration($module, $data);
        }

        return $this->test;
    }

    /**
     * @return string
     */
    public function render() : string
    {
        return $this->ui->renderer()->render($this->form);
    }
}