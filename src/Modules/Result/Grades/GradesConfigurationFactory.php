<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Result\Grades;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class GradesConfigurationFactory
 *
 * @package srag\asq\Test
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