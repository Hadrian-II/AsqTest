<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Selection\Random;

use Fluxlabs\Assessment\Test\Modules\Questions\Selection\AbstractQuestionSelectionObject;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;
use srag\asq\UserInterface\Web\PostAccess;

/**
 * Class RandomQuestionSelectionObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class RandomQuestionSelectionObject extends AbstractQuestionSelectionObject
{
    use CtrlTrait;
    use LanguageTrait;
    use PostAccess;

    private ISourceObject $source;
    private ?float $points;

    public function __construct(
        ISourceObject $source,
        ?float $points = null)
    {
        $this->source = $source;
        $this->points = $points;
    }

    public function getSelectedQuestionIds() : array
    {
        return $this->source->getQuestionIds();
    }

    public function getSource(): ISourceObject
    {
        return $this->source;
    }

    public function getKey() : string
    {
        return 'select_all_of_' . $this->source->getKey();
    }

    public function storePoints() : void
    {
        $this->points = floatval($this->getPostValue($this->getPostKey()));
    }

    public function hasOverallDisplay(): bool
    {
        return true;
    }

    public function getOverallDisplay() : string
    {
        $this->setLinkParameter(RandomQuestionSelection::PARAM_SOURCE_KEY, $this->getKey());

        return sprintf(
            '<label for="%1$s">%5$s</label>
                    <input name="%1$s" type="text" value="%2$s" />
                    <button class="btn btn-default" formmethod="post" formaction="%3$s">%4$s</button>',
            $this->getPostKey(),
            $this->points,
            $this->getCommandLink(RandomQuestionSelection::CMD_SAVE_POINTS),
            $this->txt('asqt_select'),
            $this->txt('asqt_points'));
    }

    private function getPostKey() : string
    {
        return RandomQuestionSelection::PARAM_POINTS . $this->getKey();
    }

    public function getConfiguration(): ObjectConfiguration
    {
        return new RandomQuestionSelectionConfiguration(
            $this->source->getKey(),
            $this->points);
    }

    public function selectQuestionsForRun(array $questions): array
    {
        $processor = new RandomSelectionProcessor($this->points, $questions);
        return $processor->selectQuestions();
    }
}