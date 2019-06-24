<?php

class AmazonNodePathsDe extends \Phalcon\Mvc\Model
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
    public $nodeId;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $name_remark;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var integer
     */
    public $level;

    /**
     *
     * @var integer
     */
    public $is_end_point;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     *
     * @var string
     */
    public $parent_name;

    /**
     *
     * @var string
     */
    public $first_level_category;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'amazon_node_paths_de';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AmazonNodePathsDe[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AmazonNodePathsDe
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
