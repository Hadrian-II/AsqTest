<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use srag\CQRS\Command\AbstractCommand;
use Fluxlabs\Assessment\Test\Domain\Result\Model\AssessmentResultContext;
use ILIAS\Data\UUID\Uuid;

/**
 * Class StartAssessmentCommand
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class StartAssessmentCommand extends AbstractCommand
{
    protected AssessmentResultContext $context;

    /**
     * @var Uuid[]
     */
    protected array $question_ids;

    protected Uuid $uuid;

    public function __construct(Uuid $uuid, int $user_id, AssessmentResultContext $context, array $question_ids)
    {
        $this->uuid = $uuid;
        $this->context = $context;
        $this->question_ids = $question_ids;
        parent::__construct($user_id);
    }

    public function getUuid() : Uuid
    {
        return $this->uuid;
    }

    public function getContext() : AssessmentResultContext
    {
        return $this->context;
    }

    /**
     * @return Uuid[]
     */
    public function getQuestionIds() : array
    {
        return $this->question_ids;
    }
}
