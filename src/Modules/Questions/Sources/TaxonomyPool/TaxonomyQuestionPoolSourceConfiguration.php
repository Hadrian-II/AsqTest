<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\TaxonomyPool;

use Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool\QuestionPoolSourceConfiguration;
use ILIAS\Data\UUID\Uuid;

/**
 * Class TaxonomyQuestionPoolSourceConfiguration
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TaxonomyQuestionPoolSourceConfiguration extends QuestionPoolSourceConfiguration
{
    protected ?int $selected_taxonomy_id;

    /**
     * @var ?int[]
     */
    protected ?array $used_taxonomies;

    public function __construct(?Uuid $uuid = null, int $selected_taxonomy_id = null, array $used_taxonomies = null)
    {
        parent::__construct($uuid);

        $this->selected_taxonomy_id = $selected_taxonomy_id;
        $this->used_taxonomies = $used_taxonomies;
    }

    public function getSelectedTaxonomyId() : ?int
    {
        return $this->selected_taxonomy_id;
    }

    /**
     * @return ?int[]
     */
    public function getUsedTaxonomies() : ?array
    {
        return $this->used_taxonomies;
    }

    public function moduleName(): string
    {
        return TaxonomyQuestionPoolSource::class;
    }
}