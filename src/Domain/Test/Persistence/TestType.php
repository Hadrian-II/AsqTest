<?php

namespace srag\asq\Test\Domain\Test\Persistence;

use ActiveRecord;

/**
 * Class TestType
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class TestType extends ActiveRecord
{
    const STORAGE_NAME = "asq_test_type";
    /**
     * @var int
     *
     * @con_is_primary true
     * @con_is_unique  true
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     * @con_sequence   true
     */
    protected $id;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     32
     * @con_is_notnull true
     */
    protected $key;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     255
     * @con_is_notnull true
     */
    protected $description;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     128
     * @con_is_notnull true
     */
    protected $icon;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     128
     * @con_is_notnull true
     */
    protected $main_class;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     128
     * @con_is_notnull true
     */
    protected $access_class;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     128
     * @con_is_notnull true
     */
    protected $player_class;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     128
     * @con_is_notnull true
     */
    protected $scoring_class;

    /**
     * @param string $key
     * @param string $icon
     * @param string $access
     * @param string $player
     * @param string $scoring
     * @return TestType
     */
    public static function create(
        string $key,
        string $description,
        string $icon,
        string $access,
        string $player,
        string $scoring,
        string $main) : TestType
    {
        $object = new TestType();
        $object->key = $key;
        $object->description = $description;
        $object->icon = $icon;
        $object->access_class = $access;
        $object->player_class = $player;
        $object->scoring_class = $scoring;
        $object->main_class = $main;
        return $object;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getIcon() : string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getAccessClass() : string
    {
        return $this->access_class;
    }

    /**
     * @return string
     */
    public function getPlayerClass() : string
    {
        return $this->player_class;
    }

    /**
     * @return string
     */
    public function getScoringClass() : string
    {
        return $this->scoring_class;
    }

    /**
     * @return string
     */
    public function getMainClass() : string
    {
        return $this->main_class;
    }
}