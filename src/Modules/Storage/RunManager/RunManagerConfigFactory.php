<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Storage\RunManager;

use DateTimeImmutable;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class RunManagerConfigFactory
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RunManagerConfigFactory extends AbstractObjectFactory
{
    const VAR_START = 'rm_start';
    const VAR_END = 'rm_end';

    /**
     * @param RunManagerConfig|null $value
     * @return array
     */
    public function getFormfields(?AbstractValueObject $value): array
    {
        $start = $this->factory->input()->field()->dateTime(
            $this->language->txt('asqt_starttime')
        )->withUseTime(true);
        $end = $this->factory->input()->field()->dateTime(
            $this->language->txt('asqt_endtime'),
            $this->language->txt('asqt_endtime_elaberation')
        )->withUseTime(true);

        if ($value !== null) {
            $start = $start->withValue($value->getStart()->format('Y-m-d H:i'));
            $end = $end->withValue($value->getEnd()->format('Y-m-d H:i'));
        }

        return [
            self::VAR_START => $start,
            self::VAR_END => $end
        ];
    }

    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return RunManagerConfig::create(
            $postdata[self::VAR_START],
            $postdata[self::VAR_END]
        );
    }

    public function getDefaultValue(): AbstractValueObject
    {
        return new RunManagerConfig();
    }
}