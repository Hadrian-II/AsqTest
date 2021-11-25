<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page\TestOverview;

use ILIAS\Data\UUID\Uuid;

/**
 * Class OverviewState
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class OverviewState
{
    const STATE_OPEN = 1;
    const STATE_ANSWERED = 2;

    private int $state;
    private string $text;
    private Uuid $question_id;

    public function __construct(int $state, string $text, Uuid $question_id)
    {
        $this->state = $state;
        $this->text = $text;
        $this->question_id = $question_id;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getQuestionId(): Uuid
    {
        return $this->question_id;
    }
}