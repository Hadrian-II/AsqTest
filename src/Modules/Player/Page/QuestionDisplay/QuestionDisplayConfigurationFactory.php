<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page\QuestionDisplay;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Component\QuestionComponent;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class QuestionDisplayConfigurationFactory
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDisplayConfigurationFactory extends AbstractObjectFactory
{
    const VAR_DISPLAY_MODE = 'qdc_display_mode';

    public function getFormfields(?AbstractValueObject $value): array
    {
        $display_mode = $this->factory->input()->field()->select(
            $this->language->txt('asqt_label_qdc_display_mode'),
            [
                QuestionComponent::SHOW_HEADER => $this->language->txt('asqt_label_qdc_header'),
                QuestionComponent::SHOW_HEADER_WITH_POINTS => $this->language->txt('asqt_label_qdc_header_points'),
                QuestionComponent::SHOW_NOTHING => $this->language->txt('asqt_label_qdc_nothing')
            ]
        );

        if ($value !== null) {
            $display_mode = $display_mode->withValue($value->getTitleDisplayMode());
        }

        return [
            self::VAR_DISPLAY_MODE => $display_mode
        ];
    }

    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new QuestionDisplayConfiguration($this->readInt($postdata[self::VAR_DISPLAY_MODE]));
    }

    public function getDefaultValue(): AbstractValueObject
    {
        return new QuestionDisplayConfiguration(QuestionComponent::SHOW_HEADER);
    }
}