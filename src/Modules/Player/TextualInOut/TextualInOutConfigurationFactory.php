<?php
declare(strict_types = 1);

namespace srag\asq\Test\Modules\Player\TextualInOut;

use srag\CQRS\Aggregate\AbstractValueObject;
use srag\asq\UserInterface\Web\Form\Factory\AbstractObjectFactory;

/**
 * Class TextualInOutConfigurationFactory
 *
 * @package srag\asq\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class TextualInOutConfigurationFactory extends AbstractObjectFactory
{
    const VAR_INTRO_TEXT = 'tio_intro_text';
    const VAR_OUTRO_TEXT = 'tio_outro_text';

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getFormfields()
     */
    public function getFormfields(?AbstractValueObject $value): array
    {
        $intro = $this->factory->input()->field()->textarea($this->language->txt('label_tio_intro_text'));
        $outro = $this->factory->input()->field()->textarea($this->language->txt('label_tio_outro_text'));

        if ($value !== null) {
            $intro = $intro->withValue($value->getIntroText());
            $outro = $outro->withValue($value->getOutroText());
        }

        return [
            self::VAR_INTRO_TEXT => $intro,
            self::VAR_OUTRO_TEXT => $outro
        ];
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::readObjectFromPost()
     */
    public function readObjectFromPost(array $postdata): AbstractValueObject
    {
        return new TextualInOutConfiguration(
            $this->readString($postdata[self::VAR_INTRO_TEXT]),
            $this->readString($postdata[self::VAR_OUTRO_TEXT])
        );
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\UserInterface\Web\Form\Factory\IObjectFactory::getDefaultValue()
     */
    public function getDefaultValue(): AbstractValueObject
    {
        return new TextualInOutConfiguration();
    }
}