<?php
declare(strict_types = 1);

namespace Fluxlabs\Assessment\Test\Domain\Section\Model;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AssessmentSectionData
 *
 * @package Fluxlabs\Assessment\Test
 *
 * @author Fluxlabs AG - Adrian Lüthi <adi@fluxlabs.ch>
 */
class AssessmentSectionData extends AbstractValueObject
{
    /**
     * QTI
     *
     * The title of the section is intended to enable the section to be selected in situations
     * where the contents of the section are not available, for example when a candidate is browsing a test.
     * Therefore, delivery engines may reveal the title to candidates at any time during the test but are not
     * required to do so.
     */
    protected string $title;

    /**
     * QTI
     *
     * If a child element is required it must appear (at least once) in the selection. It is an error if a
     * section contains a selection rule that selects fewer child elements than the number of required elements
     * it contains.
     */
    protected bool $required;

    /**
     * QTI
     *
     * If a child element is fixed it must never be shuffled. When used in combination with a selection rule
     * fixed elements do not have their position fixed until after selection has taken place. For example,
     * selecting 3 elements from {A,B,C,D} without replacement might result in the selection {A,B,C}.
     * If the section is subject to shuffling but B is fixed then permutations such as {A,C,B} are not allowed
     * whereas permutations like {C,B,A} are.
     */
    protected bool $fixed;

    /**
     * QTI
     *
     * A visible section is one that is identifiable by the candidate. For example, delivery engines
     * might provide a hierarchical view of the test to aid navigation. In such a view, a visible section
     * would be a visible node in the hierarchy. Conversely, an invisible section is one that is not visible
     * to the candidate - the child elements of an invisible section appear to the candidate as if they were
     * part of the parent section (or testPart). The visibility of a section does not affect the visibility
     * of its child elements. The visibility of each section is determined solely by the value of its own
     * visible attribute.
     */
    protected bool $visible;

    /**
     * QTI
     *
     * An invisible section with a parent that is subject to shuffling can specify whether or not its children,
     * which will appear to the candidate as if they were part of the parent, are shuffled as a block or mixed
     * up with the other children of the parent section.
     */
    protected bool $keep_together;

    /**
     * QTI
     *
     * If specified, this has a value that is a set of space-separated tokens representing
     * the various classes to which the element belongs.
     *
     * @var String[]
     */
    protected array $class = [];

    /**
     * Configs belonging to the classes defined in $class
     *
     * @var AbstractValueObject[]
     */
    protected array $class_configs =[];

    public function __construct(
        string $title = '',
        bool $visible = true,
        bool $required = false,
        bool $fixed = false,
        bool $keep_together = true
    ) {
        $this->title = $title;
        $this->visible = $visible;
        $this->required = $required;
        $this->fixed = $fixed;
        $this->keep_together = $keep_together;
    }

    public function addClass(string $name, ?AbstractValueObject $config = null) : void
    {
        $this->class[] = $name;

        if ($config !== null) {
            $this->class_configs[$name] = $config;
        }
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function isRequired() : bool
    {
        return $this->required;
    }

    public function isFixed() : bool
    {
        return $this->fixed;
    }

    public function isVisible() : bool
    {
        return $this->visible;
    }

    public function isKeepTogether() : bool
    {
        return $this->keep_together;
    }

    /**
     * @return String[]
     */
    public function getClasses() : array
    {
        return $this->class;
    }

    public function getData(string $class) : AbstractValueObject
    {
        return $this->class_configs[$class];
    }
}
