<?php

class Uploadlog extends \Phalcon\Mvc\Model
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
    public $submission_id;

    /**
     *
     * @var string
     */
    public $quid;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $detail_url;

    /**
     *
     * @var integer
     */
    public $product_count;

    /**
     *
     * @var integer
     */
    public $variation_count;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'uploadlog';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Uploadlog[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Uploadlog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
