<?php

class Shop extends \Phalcon\Mvc\Model
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
    public $amazon_merchant_id;

    /**
     *
     * @var string
     */
    public $amazon_token;

    /**
     *
     * @var integer
     */
    public $user;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'shop';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Shop[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Shop
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
