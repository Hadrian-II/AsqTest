<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Availability\Basic;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class BasicAvailabilityConfigurationFactory
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class BasicAvailabilityConfigurationFactory extends AbstractObjectFactory
{
    const VAR_VISIBLE_IF_UNAVAILABLE = 'bac_visible';

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getFormfields()
     */
    public function getFormfields(?AbstractValueObject $value): array
    {
        $visible = $this->factory->input()->field()->checkbox($this->language->txt('label_bac_visible'));

        if ($value !== null) {
            $visible = $visible->withValue($value->isVisibleIfUnavailable());
        }

        return [
            self::VAR_VISIBLE_IF_UNAVAILABLE => $visible
        ];
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::readObjectFromPost()
     */
    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new BasicAvailabilityConfiguration($postdata[self::VAR_VISIBLE_IF_UNAVAILABLE]);
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getDefaultValue()
     */
    public function getDefaultValue(): AbstractValueObject
    {
        return new BasicAvailabilityConfiguration(true);
    }
}