<?php

class InternationalShipping extends \Phalcon\Mvc\Model
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
    public $country;

    /**
     *
     * @var string
     */
    public $method;

    /**
     *
     * @var string
     */
    public $starting_price;

    /**
     *
     * @var string
     */
    public $starting_weight;

    /**
     *
     * @var string
     */
    public $unit_price;

    /**
     *
     * @var string
     */
    public $per_weight;

    /**
     *
     * @var string
     */
    public $registration_fee;

    /**
     *
     * @var string
     */
    public $discount;

    /**
     *
     * @var string
     */
    public $currency;

    /**
     *
     * @var string
     */
    public $weight_unit;

    /**
     *
     * @var string
     */
    public $weight_factor;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'international_shipping';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return InternationalShipping[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return InternationalShipping
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
