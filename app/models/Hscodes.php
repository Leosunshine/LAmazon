<?php

class Hscodes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $hs_code;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $unit_law_id;

    /**
     *
     * @var string
     */
    public $unit_law;

    /**
     *
     * @var string
     */
    public $unit_second_id;

    /**
     *
     * @var string
     */
    public $unit_second;

    /**
     *
     * @var string
     */
    public $remark;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'hscodes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Hscodes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Hscodes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
