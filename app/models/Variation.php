<?php

class Variation extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $SKU;

    /**
     *
     * @var string
     */
    public $EAN;

    /**
     *
     * @var string
     */
    public $amazon_status;

    /**
     *
     * @var integer
     */
    public $inventory_count;

    /**
     *
     * @var string
     */
    public $price_bonus;

    /**
     *
     * @var string
     */
    public $images;

    /**
     *
     * @var integer
     */
    public $product_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'variation';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Variation[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Variation
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
