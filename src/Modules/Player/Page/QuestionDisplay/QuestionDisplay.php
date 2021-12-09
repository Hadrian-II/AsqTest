<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Player\Page\QuestionDisplay;

use Fluxlabs\Assessment\Test\Modules\Player\IOverviewProvider;
use Fluxlabs\Assessment\Test\Modules\Player\Page\PlayerPage;
use Fluxlabs\Assessment\Test\Modules\Player\Page\QuestionDisplay\QuestionDisplayConfiguration;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\KitchenSinkTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ilTemplate;
use srag\asq\Application\Exception\AsqException;
use srag\asq\Application\Service\QuestionService;
use srag\asq\Application\Service\UIService;
use srag\asq\Domain\QuestionDto;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class QuestionDisplay
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class QuestionDisplay
{
    use KitchenSinkTrait;

    private QuestionDto $question;
    private ?QuestionDisplayConfiguration $config;
    private ?AbstractValueObject $answer;

    private UIService $asq;

    public function __construct(QuestionDto $question, ?AbstractValueObject $answer, ?QuestionDisplayConfiguration $config)
    {
        global $ASQDIC;
        $this->asq = $ASQDIC->asq()->ui();

        $this->question = $question;
        $this->answer = $answer;
        $this->config = $config;
    }

    public function render() : string
    {
        $component = $this->asq->getQuestionComponent($this->question);

        if ($this->answer !== null) {
            $component = $component->withAnswer($this->answer);
        }

        if ($this->config !== null) {
            $component = $component->withTitleDisplay($this->config->getTitleDisplayMode());
        }

        return $this->renderKSComponent($component);
    }
}