<?php

class Products extends \Phalcon\Mvc\Model
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
    public $title;

    /**
     *
     * @var string
     */
    public $keywords;

    /**
     *
     * @var string
     */
    public $keypoints;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $abbr_en;

    /**
     *
     * @var string
     */
    public $abbr_cn;

    /**
     *
     * @var string
     */
    public $ASIN;

    /**
     *
     * @var string
     */
    public $SKU;

    /**
     *
     * @var string
     */
    public $images;

    /**
     *
     * @var integer
     */
    public $main_image_id;

    /**
     *
     * @var integer
     */
    public $product_count;

    /**
     *
     * @var integer
     */
    public $perpackage_count;

    /**
     *
     * @var integer
     */
    public $review_status;

    /**
     *
     * @var integer
     */
    public $appear_status;

    /**
     *
     * @var string
     */
    public $amazon_status;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $security_status;

    /**
     *
     * @var integer
     */
    public $product_level;

    /**
     *
     * @var string
     */
    public $brand;

    /**
     *
     * @var string
     */
    public $label;

    /**
     *
     * @var string
     */
    public $developer;

    /**
     *
     * @var string
     */
    public $artist;

    /**
     *
     * @var string
     */
    public $manufacturer;

    /**
     *
     * @var string
     */
    public $manufacturer_id;

    /**
     *
     * @var string
     */
    public $origin_place;

    /**
     *
     * @var string
     */
    public $catalog_number;

    /**
     *
     * @var integer
     */
    public $amazon_category_id;

    /**
     *
     * @var string
     */
    public $category_local;

    /**
     *
     * @var string
     */
    public $amazon_node_path;

    /**
     *
     * @var string
     */
    public $amazon_nodeId;

    /**
     *
     * @var string
     */
    public $customs_hscode;

    /**
     *
     * @var string
     */
    public $customs_price;

    /**
     *
     * @var string
     */
    public $package_weight;

    /**
     *
     * @var string
     */
    public $package_length;

    /**
     *
     * @var string
     */
    public $package_width;

    /**
     *
     * @var string
     */
    public $package_height;

    /**
     *
     * @var string
     */
    public $package_remark;

    /**
     *
     * @var integer
     */
    public $supplier_id;

    /**
     *
     * @var string
     */
    public $item_serial_number;

    /**
     *
     * @var string
     */
    public $resource_url;

    /**
     *
     * @var string
     */
    public $supply_remark;

    /**
     *
     * @var integer
     */
    public $design_for_id;

    /**
     *
     * @var integer
     */
    public $matel_type_id;

    /**
     *
     * @var string
     */
    public $package_material_type;

    /**
     *
     * @var integer
     */
    public $jewel_type_id;

    /**
     *
     * @var string
     */
    public $ringsize;

    /**
     *
     * @var integer
     */
    public $number_of_items;

    /**
     *
     * @var integer
     */
    public $package_quantity;

    /**
     *
     * @var string
     */
    public $part_number;

    /**
     *
     * @var string
     */
    public $product_group;

    /**
     *
     * @var string
     */
    public $product_type_name;

    /**
     *
     * @var string
     */
    public $publisher;

    /**
     *
     * @var string
     */
    public $studio;

    /**
     *
     * @var string
     */
    public $color;

    /**
     *
     * @var string
     */
    public $binding;

    /**
     *
     * @var string
     */
    public $is_adult_product;

    /**
     *
     * @var string
     */
    public $is_memorabilia;

    /**
     *
     * @var string
     */
    public $material_type;

    /**
     *
     * @var string
     */
    public $weight;

    /**
     *
     * @var string
     */
    public $small_image;

    /**
     *
     * @var string
     */
    public $price;

    /**
     *
     * @var string
     */
    public $currency;

    /**
     *
     * @var string
     */
    public $distribution_price;

    /**
     *
     * @var integer
     */
    public $is_distribution;

    /**
     *
     * @var string
     */
    public $fixed_shipping;

    /**
     *
     * @var integer
     */
    public $international_shipping_id;

    /**
     *
     * @var string
     */
    public $variation_theme;

    /**
     *
     * @var string
     */
    public $variation_node;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'products';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Products[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Products
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
