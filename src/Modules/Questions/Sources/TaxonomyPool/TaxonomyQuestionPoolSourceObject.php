<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\TaxonomyPool;

use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;
use Fluxlabs\Assessment\Test\Modules\Questions\AbstractQuestionObject;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool\QuestionPoolSourceObject;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\DIC\LanguageTrait;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use Fluxlabs\Assessment\Tools\Service\Taxonomy\Taxonomy;
use ILIAS\Data\UUID\Uuid;
use srag\asq\QuestionPool\Application\QuestionPoolService;
use srag\asq\QuestionPool\Module\Taxonomy\TaxonomyData;
use srag\asq\QuestionPool\Module\Taxonomy\TaxonomyModule;
use srag\asq\UserInterface\Web\Form\InputHandlingTrait;
use srag\asq\UserInterface\Web\PostAccess;

/**
 * Class TaxonomyQuestionPoolSourceObject
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TaxonomyQuestionPoolSourceObject extends AbstractQuestionObject implements ISourceObject
{
    use CtrlTrait;
    use PostAccess;
    use LanguageTrait;

    const PARAM_SELECTION = 'taxonomy_';

    private QuestionPoolService $pool_service;

    private TaxonomyQuestionPoolSourceConfiguration $configuration;

    private ?TaxonomyData $data = null;

    private ?Taxonomy $taxonomy = null;

    private bool $has_no_taxonomy = false;

    public function __construct(TaxonomyQuestionPoolSourceConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->pool_service = new QuestionPoolService();

        $this->data = $this->pool_service->getConfiguration($this->configuration->getUuid(), TaxonomyModule::TAXONOMY_KEY);
        if ($this->data === null) {
            $this->has_no_taxonomy = true;
        }
        else
        {
            $this->taxonomy = new Taxonomy($this->data->getTaxonomyId());
        }
    }

    public function getConfiguration(): ObjectConfiguration
    {
        return $this->configuration;
    }

    public function getKey(): string
    {
        return 'taxonomy_pool_source_for_pool' . $this->configuration->getUuid()->toString();
    }

    public function getQuestions(): array
    {
        return $this->configuration->getQuestions();
    }

    public function setSelections(array $selections): void
    {
        $this->configuration = new TaxonomyQuestionPoolSourceConfiguration(
            $this->configuration->getUuid(),
            $selections,
            $this->configuration->getSelectedTaxonomyId(),
            $this->configuration->getUsedTaxonomies()
        );
    }

    public function getAllQuestions(): array
    {
        $questions = $this->pool_service->getQuestionsOfPool($this->configuration->getUuid());

        //no filtering if no available taxonomies
        if ($this->has_no_taxonomy) {
            return $questions;
        }

        //no filtering if no taxonomy selected
        if ($this->configuration->getUsedTaxonomies() === null) {
            return $questions;
        }

        return array_reduce($questions, function($matched_questions, $question)
        {
            $question_taxonomy = $this->data->getQuestionMapping()[strval($question)];

            if (in_array($question_taxonomy, $this->configuration->getUsedTaxonomies())) {
                $matched_questions[] = $question;
            }

            return $matched_questions;
        }, []);
    }

    public function hasOverallDisplay(): bool
    {
        return true;
    }

    public function getOverallDisplay() : string
    {
        if ($this->has_no_taxonomy) {
            return '';
        }

        $mapping = $this->taxonomy->getNodeMapping();

        $options = implode('', array_map(function($node) {
            return sprintf(
                '<option value="%s" %s>%s</option>',
                $node['obj_id'],
                strval($this->configuration->getSelectedTaxonomyId()) === $node['obj_id'] ? 'selected="selected"' : '',
                $node['title']);
        }, $mapping));

        $this->setLinkParameter(TaxonomyQuestionPoolSource::PARAM_SOURCE_KEY, $this->getKey());

        return sprintf(
            '<select name="%s"><option value="">---</option>%s</select>
                    <button class="btn btn-default" formmethod="post" formaction="%s">%s</button>',
            $this->getSelectionPostKey(),
            $options,
            $this->getCommandLink(TaxonomyQuestionPoolSource::CMD_TAXONOMY_SELECTION),
            $this->txt('asqt_select_taxonomy'));
    }

    public function storeTaxonomySelection() : void
    {
        $selected_taxonomy = intval($this->getPostValue($this->getSelectionPostKey()));

        $this->configuration = new TaxonomyQuestionPoolSourceConfiguration(
            $this->configuration->getUuid(),
            $this->configuration->getQuestions(),
            $selected_taxonomy,
            $this->taxonomy->getTaxonomyWithChildren($selected_taxonomy)
        );
    }

    public function getSelectionPostKey() : string
    {
        return self::PARAM_SELECTION . $this->configuration->getUuid()->toString();
    }

    public function isValid(): bool
    {
        return count($this->getQuestions()) > 0;
    }
}