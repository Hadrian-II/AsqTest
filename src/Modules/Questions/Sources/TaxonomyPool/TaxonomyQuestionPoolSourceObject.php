<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\TaxonomyPool;

use Fluxlabs\Assessment\Test\Application\Test\Object\ISourceObject;
use Fluxlabs\Assessment\Test\Modules\Questions\AbstractQuestionObject;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool\QuestionPoolSourceObject;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
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

    const PARAM_SELECTION = 'taxonomy_';

    private QuestionPoolService $pool_service;

    private TaxonomyQuestionPoolSourceConfiguration $configuration;

    private ?TaxonomyData $data = null;

    private ?Taxonomy $taxonomy = null;

    public function __construct(TaxonomyQuestionPoolSourceConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->pool_service = new QuestionPoolService();
    }

    private function getData() : TaxonomyData
    {
        if ($this->data === null) {
            $this->data = $this->pool_service->getConfiguration($this->configuration->getUuid(), TaxonomyModule::TAXONOMY_KEY);
        }

        return $this->data;
    }

    private function getTaxonomy() : Taxonomy
    {
        if ($this->taxonomy === null) {
            $this->taxonomy = new Taxonomy($this->getData()->getTaxonomyId());
        }

        return $this->taxonomy;
    }

    public function getConfiguration(): ObjectConfiguration
    {
        return $this->configuration;
    }

    public function getKey(): string
    {
        return 'taxonomy_pool_source_for_pool' . $this->configuration->getUuid();
    }

    public function getQuestionIds(): array
    {
        $questions = $this->pool_service->getQuestionsOfPool($this->configuration->getUuid());

        return array_reduce($questions, function($matched_questions, $question)
        {
            $question_taxonomy = $this->getData()->getQuestionMapping()[strval($question)];

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
        $mapping = $this->getTaxonomy()->getNodeMapping();

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
            'TODO Select Taxonomy');
    }

    public function storeTaxonomySelection() : void
    {
        $selected_taxonomy = intval($this->getPostValue($this->getSelectionPostKey()));

        $this->configuration = new TaxonomyQuestionPoolSourceConfiguration(
            $this->configuration->getUuid(),
            $selected_taxonomy,
            $this->getTaxonomy()->getTaxonomyWithChildren($selected_taxonomy)
        );
    }

    public function getSelectionPostKey() : string
    {
        return self::PARAM_SELECTION . $this->configuration->getUuid();
    }
}