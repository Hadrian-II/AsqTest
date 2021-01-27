<?php
declare(strict_types = 1);

namespace srag\asq\Test\Application\TestRunner\Command;

use srag\CQRS\Command\AbstractCommand;
use srag\asq\Test\Domain\Result\Model\AssessmentResultContext;
use ILIAS\Data\UUID\Uuid;

/**
 * Class StartAssessmentCommand
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class StartAssessmentCommand extends AbstractCommand
{
    /**
     * @var AssessmentResultContext
     */
    protected $context;

    /**
     * @var Uuid[]
     */
    protected $question_ids;

    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @param Uuid $uuid
     * @param int $user_id
     * @param AssessmentResultContext $context
     * @param array $question_ids
     */
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

    /**
     * @return AssessmentResultContext
     */
    public function getContext()
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
