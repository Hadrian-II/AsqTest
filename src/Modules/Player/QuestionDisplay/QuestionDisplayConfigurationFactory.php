<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\QuestionDisplay;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class QuestionDisplayConfigurationFactory
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionDisplayConfigurationFactory extends AbstractObjectFactory
{
    const VAR_DISPLAY_MODE = 'qdc_display_mode';

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getFormfields()
     */
    public function getFormfields(?AbstractValueObject $value): array
    {
        $display_mode = $this->factory->input()->field()->select(
            $this->language->txt('label_qdc_display_mode'),
            [
                QuestionDisplayConfiguration::SHOW_HEADER => $this->language->txt('label_qdc_header'),
                QuestionDisplayConfiguration::SHOW_HEADER_WITH_POINTS => $this->language->txt('label_qdc_header_points'),
                QuestionDisplayConfiguration::SHOW_NOTHING => $this->language->txt('label_qdc_nothing')
            ]
        );

        if ($value !== null) {
            $display_mode = $display_mode->withValue($value->getHeaderDisplayMode());
        }

        return [
            self::VAR_DISPLAY_MODE => $display_mode
        ];
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::readObjectFromPost()
     */
    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new QuestionDisplayConfiguration($this->readInt($postdata[self::VAR_DISPLAY_MODE]));
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getDefaultValue()
     */
    public function getDefaultValue(): AbstractValueObject
    {
        return new QuestionDisplayConfiguration(QuestionDisplayConfiguration::SHOW_HEADER);
    }
}