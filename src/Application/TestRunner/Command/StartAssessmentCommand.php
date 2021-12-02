<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Application\TestRunner\Command;

use Fluxlabs\Assessment\Test\Domain\Result\Model\QuestionDefinition;
use Fluxlabs\CQRS\Command\AbstractCommand;
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
     * @var QuestionDefinition[]
     */
    protected array $question_ids;

    protected Uuid $uuid;

    public function __construct(Uuid $uuid, AssessmentResultContext $context, array $question_ids)
    {
        $this->uuid = $uuid;
        $this->context = $context;
        $this->question_ids = $question_ids;
        parent::__construct();
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
     * @return QuestionDefinition[]
     */
    public function getQuestionIds() : array
    {
        return $this->question_ids;
    }
}
