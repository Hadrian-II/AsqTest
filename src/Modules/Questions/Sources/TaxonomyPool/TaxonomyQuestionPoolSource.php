<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Modules\Questions\Sources\TaxonomyPool;

use Fluxlabs\Assessment\Test\Modules\Questions\Page\QuestionPage;
use Fluxlabs\Assessment\Test\Modules\Questions\Sources\Pool\QuestionPoolSource;
use Fluxlabs\Assessment\Tools\DIC\CtrlTrait;
use Fluxlabs\Assessment\Tools\Domain\Objects\IAsqObject;
use Fluxlabs\Assessment\Tools\Domain\Objects\ObjectConfiguration;
use Fluxlabs\Assessment\Tools\Event\Standard\ForwardToCommandEvent;
use Fluxlabs\Assessment\Tools\Event\Standard\StoreObjectEvent;
use ILIAS\Data\UUID\Factory;

/**
 * Class TaxonomyQuestionPoolSource
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TaxonomyQuestionPoolSource extends QuestionPoolSource
{
    use CtrlTrait;

    const PARAM_SOURCE_KEY = 'sourceKey';

    const CMD_TAXONOMY_SELECTION = 'saveTaxonomySelection';

    protected function qpsCreate() : void {
        $factory = new Factory();
        $uuid = $factory->fromString($this->getLinkParameter(self::PARAM_SELECTED_POOL));

        $pool_source = new TaxonomyQuestionPoolSourceObject(new TaxonomyQuestionPoolSourceConfiguration($uuid));

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $pool_source
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::SHOW_QUESTIONS
        ));
    }

    /**
     * @param TaxonomyQuestionPoolSourceConfiguration $config
     * @return IAsqObject
     */
    public function createObject(ObjectConfiguration $config) : IAsqObject
    {
        return new TaxonomyQuestionPoolSourceObject($config);
    }

    public function getQuestionPageActions(IAsqObject $object): string
    {
        return parent::getQuestionPageActions($object); // TODO: Change the autogenerated stub
    }

    public function saveTaxonomySelection() : void
    {
        $selection_key = $this->getLinkParameter(self::PARAM_SOURCE_KEY);

        /** @var TaxonomyQuestionPoolSourceObject $source */
        $source = $this->access->getObject($selection_key);

        $source->storeTaxonomySelection();

        $this->raiseEvent(new StoreObjectEvent(
            $this,
            $source
        ));

        $this->raiseEvent(new ForwardToCommandEvent(
            $this,
            QuestionPage::SHOW_QUESTIONS
        ));
    }

    public function getCommands(): array
    {
        return [
            self::SHOW_POOL_SELECTION,
            self::CREATE_POOL_SOURCE,
            self::CMD_TAXONOMY_SELECTION
        ];
    }
}