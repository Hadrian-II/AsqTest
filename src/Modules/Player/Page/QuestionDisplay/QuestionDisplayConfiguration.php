<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page\QuestionDisplay;

use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class QuestionDisplayConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDisplayConfiguration extends AbstractValueObject
{
    protected ?int $title_display_mode;

    public function __construct(?int $title_display_mode = null)
    {
        $this->title_display_mode = $title_display_mode;
    }

    public function getTitleDisplayMode() : ?int
    {
        return $this->title_display_mode;
    }
}