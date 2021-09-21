<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Result\Grades;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class GradesConfigurationFactory
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class GradesConfigurationFactory extends AbstractObjectFactory
{
    public function getFormfields(?AbstractValueObject $value): array
    {

    }

    public function readObjectFromPost(array $postdata): AbstractValueObject
    {

    }

    public function getDefaultValue(): AbstractValueObject
    {

    }
}