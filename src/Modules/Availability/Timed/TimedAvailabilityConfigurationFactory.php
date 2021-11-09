<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Availability\Timed;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class TimedAvailabilityConfigurationFactory
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TimedAvailabilityConfigurationFactory extends AbstractObjectFactory
{
    const VAR_FROM = 'tac_from';
    const VAR_TO = 'tac_to';

    public function getFormfields(?AbstractValueObject $value): array
    {
        $from = $this->factory->input()->field()->dateTime($this->language->txt('label_tac_from'));
        $to = $this->factory->input()->field()->dateTime($this->language->txt('label_tac_to'));

        if ($value !== null) {
            if ($value->getAvailableFrom() !== null) {
                $from = $from->withMinValue($value->getAvailableFrom());
            }
            if ($value->getAvailableTo() !== null) {
                $to = $to->withMinValue($value->getAvailableTo());
            }
        }

        return [
            self::VAR_FROM => $from,
            self::VAR_TO => $to
        ];
    }

    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new TimedAvailabilityConfiguration(
            $postdata[self::VAR_FROM],
            $postdata[self::VAR_TO]
        );
    }

    public function getDefaultValue(): AbstractValueObject
    {
        return new TimedAvailabilityConfiguration();
    }
}