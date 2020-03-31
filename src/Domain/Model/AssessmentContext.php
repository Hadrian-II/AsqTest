<?php

namespace srag\asq\Test\DomainModel;

use srag\CQRS\Aggregate\AbstractValueObject;

/**
 * Class AssessmentContext
 *
 * @package srag\asq\Test
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class AssessmentContext extends AbstractValueObject {
    /**
     * @var int
     */
    protected $user_id;
    /**
     * @var string
     */
    protected $assessment_name;
    /**
     * @var int
     */
    protected $run;
    /**
     * @var ?string
     */
    protected $assessment_id;
    /**
     * @var ?string
     */
    protected $assessment_revision;
    
    public static function create(int $user_id, 
                                  string $assessment_name, 
                                  int $run = 1, 
                                  ?string $assessment_id = null, 
                                  ?string $assessment_revision = null) : AssessmentContext
    {
        $object = new AssessmentContext();
        $object->user_id = $user_id;
        $object->assessment_name = $assessment_name;
        $object->run = $run;
        $object->assessment_id = $assessment_id;
        $object->assessment_revision = $assessment_revision;
        return $object;
    }
    
    /**
     * @return int
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getAssessment_name()
    {
        return $this->assessment_name;
    }

    /**
     * @return number
     */
    public function getRun()
    {
        return $this->run;
    }

    /**
     * @return ?string
     */
    public function getAssessment_id()
    {
        return $this->assessment_id;
    }

    /**
     * @return ?string
     */
    public function getAssessment_revision()
    {
        return $this->assessment_revision;
    }
}