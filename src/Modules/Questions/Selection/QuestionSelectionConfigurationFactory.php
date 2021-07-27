<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Questions\Selection;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class QuestionSelectionConfigurationFactory
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionSelectionConfigurationFactory extends AbstractObjectFactory
{
    const VAR_SELECTION_TYPE = 'qsc_selection_type';
    const VAR_RANDOM_POINTS = 'qsc_random_points';

    public function getFormfields(?AbstractValueObject $value): array
    {
        $random_points = $this->factory->input()->field()->numeric($this->language->txt('label_qsc_random_points'));

        if ($value !== null) {
            $random_points = $random_points->withValue($value->getRandomAmount());
        }

        $selection_type = $this->factory->input()->field()->switchableGroup(
            [
                QuestionSelectionConfiguration::ALL_QUESTIONS =>
                $this->factory->input()->field()->group(
                    [],
                    $this->language->txt('label_qsc_all')
                    ),
                QuestionSelectionConfiguration::SELECTED_QUESTIONS =>
                $this->factory->input()->field()->group(
                    [],
                    $this->language->txt('label_qsc_selected')
                    ),
                QuestionSelectionConfiguration::RANDOM_QUESTIONS =>
                $this->factory->input()->field()->group(
                    [
                        self::VAR_RANDOM_POINTS => $random_points
                    ],
                    $this->language->txt('label_qsc_random')
                    ),
            ],
            $this->language->txt('label_qsc_selection_type')
        );

        if ($value !== null) {
            $selection_type = $selection_type->withValue($value->getSelectionType());
        }

        return [
            self::VAR_SELECTION_TYPE => $selection_type
        ];
    }

    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new QuestionSelectionConfiguration(
            $this->readInt($postdata[self::VAR_SELECTION_TYPE][0]),
            $this->readInt($postdata[self::VAR_SELECTION_TYPE][1][self::VAR_RANDOM_POINTS])
        );
    }

    public function getDefaultValue(): AbstractValueObject
    {
        return new QuestionSelectionConfiguration(QuestionSelectionConfiguration::ALL_QUESTIONS);
    }
}