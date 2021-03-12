<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Availability\Timed;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class TimedAvailabilityConfigurationFactory
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TimedAvailabilityConfigurationFactory extends AbstractObjectFactory
{
    const VAR_FROM = 'tac_from';
    const VAR_TO = 'tac_to';

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getFormfields()
     */
    public function getFormfields(?AbstractValueObject $value): array
    {
        $from = $this->factory->input()->field()->dateTime($this->language->txt('label_tac_from'));
        $to = $this->factory->input()->field()->dateTime($this->language->txt('label_tac_to'));

        if ($value !== null) {
            $from = $from->withMinValue($value->getAvailableFrom());
            $to = $to->withMinValue($value->getAvailableTo());
        }

        return [
            self::VAR_FROM => $from,
            self::VAR_TO => $to
        ];
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::readObjectFromPost()
     */
    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new TimedAvailabilityConfiguration(
            $postdata[self::VAR_FROM],
            $postdata[self::VAR_TO]
        );
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getDefaultValue()
     */
    public function getDefaultValue(): AbstractValueObject
    {
        return new TimedAvailabilityConfiguration();
    }
}