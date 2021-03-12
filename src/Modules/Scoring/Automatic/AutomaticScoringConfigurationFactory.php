<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Scoring\Automatic;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class AutomaticScoringConfigurationFactory
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AutomaticScoringConfigurationFactory extends AbstractObjectFactory
{
    const VAR_SCORING_MODE = 'asc_scoring_mode';
    const VAR_ALLOW_NEGATIVE = 'asc_allow_negative';

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getFormfields()
     */
    public function getFormfields(?AbstractValueObject $value): array
    {
        $scoring_mode = $this->factory->input()->field()->select(
            $this->language->txt('label_asc_scoring_mode'),
            [
                AutomaticScoringConfiguration::SCORING_ALL_OR_NOTHING => $this->language->txt('label_asc_scoring_mode'),
                AutomaticScoringConfiguration::SCORING_PARTIAL_RESULTS => $this->language->txt('label_partial_results')
            ]
        );

        $allow_negative = $this->factory->input()->field()->checkbox($this->language->txt('label_asc_scoring_mode'));

        if ($value !== null) {
            $scoring_mode = $scoring_mode->withValue($value->getScoringMode());
            $allow_negative = $allow_negative->withValue($value->allowNegative());
        }

        return [
            self::VAR_SCORING_MODE => $scoring_mode,
            self::VAR_ALLOW_NEGATIVE => $allow_negative
        ];
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::readObjectFromPost()
     */
    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new AutomaticScoringConfiguration(
            $this->readInt($postdata[self::VAR_SCORING_MODE]),
            $postdata[self::VAR_ALLOW_NEGATIVE]
        );
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getDefaultValue()
     */
    public function getDefaultValue(): AbstractValueObject
    {
        return new AutomaticScoringConfiguration();
    }
}