<?php
define("LAMAZON_INVALID_STATUS"  , 0);
define("LAMAZON_NEVER_UPLOADED"  , 1);
define("LAMAZON_FIRST_UPLOADING" , 2);
define("LAMAZON_READY_TO_UPDATE" , 3);
define("LAMAZON_DATA_UPDATING"   , 4);
define("LAMAZON_UP_TO_DATE"      , 5);
define("LAMAZON_READY_TO_DELETE" , 6);
define("LAMAZON_DATA_DELETING"   , 7);
define("LAMAZON_DATA_DELETED"    , 8);

class AmazonStatus
{
	private static $type_index = array("Variation"=>0,"Product"=>0,"Relation"=>1,"Price"=>2,"Inventory"=>3,"Image"=>4);

	private static function getStatus($product, $type = "Product"){
		$index = AmazonStatus::$type_index[$type];
		return substr($product->amazon_status, $index, 1);
	}

	private static function setStatus($product, $status, $type = "Product"){
		if("Product" === $type){
			$product->status = $status;
		}
		$index = AmazonStatus::$type_index[$type];
		$product->amazon_status = Tools::replaceCharAt($product->amazon_status, $index, $status."");
	}
	public static function isValid($product, $isVariation = false){
		if(!$isVariation){
			$status = $product->status;
			return $status >= 1 && $status <= 5;
		}else{
			$status = AmazonStatus::getStatus($product,"Product");
			return $product->product_id != 0 && $status >= 1 && $status <= 5;
		}
	}


	public static function isNeedDelete($product){
		$status = AmazonStatus::getStatus($product,"Product");
		return $status === LAMAZON_READY_TO_DELETE;
	}

	//判断服务器中是否存在该商品
	public static function isAmazonExist($product){
		$status = AmazonStatus::getStatus($product,"Product");
		return !($status === LAMAZON_NEVER_UPLOADED || $status === LAMAZON_INVALID_STATUS || $status === LAMAZON_DATA_DELETED);
	}


	public static function isUpdating($product, $type = "Product"){
		$status = AmazonStatus::getStatus($product, $type);
		return $status === LAMAZON_FIRST_UPLOADING || $status === LAMAZON_DATA_UPDATING || $status === LAMAZON_DATA_DELETING;
	}

	public static function isNeedUpdate($product, $type = "Product"){
		$status = AmazonStatus::getStatus($product, $type);
		if("Product" === $type){
			 return $status === LAMAZON_NEVER_UPLOADED || $status === LAMAZON_READY_TO_UPDATE || $status === LAMAZON_READY_TO_DELETE;
		}else{
			if(AmazonStatus::isNeedUpdate($product, "Product")) return false;
			$status_product = AmazonStatus::getStatus($product,"Product");
			if($status_product === LAMAZON_DATA_DELETING || $status_product === LAMAZON_DATA_DELETED) return false;
			return $status === LAMAZON_NEVER_UPLOADED || $status === LAMAZON_READY_TO_UPDATE;
		}
	}

	//将相应的状态位设置为上传状态 1->2  3->4  6->7
	public static function setUpdating($product,$type){
		$status = AmazonStatus::getStatus($product,$type);
		switch ($status) {
			case LAMAZON_NEVER_UPLOADED:
				AmazonStatus::setStatus($product, LAMAZON_FIRST_UPLOADING, $type);break;
			case LAMAZON_READY_TO_UPDATE:
				AmazonStatus::setStatus($product, LAMAZON_DATA_UPDATING, $type); break;
			case LAMAZON_READY_TO_DELETE:
				AmazonStatus::setStatus($product, LAMAZON_DATA_DELETING, $type); break;
			default: break;
		}
	}

	//将相应的状态位设置为同步状态  2->5 4->5 7->8
	public static function setUpdated($product, $type){
		$status = AmazonStatus::getStatus($product, $type);
		switch ($status) {
			case LAMAZON_FIRST_UPLOADING:
			case LAMAZON_DATA_UPDATING:
				AmazonStatus::setStatus($product, LAMAZON_UP_TO_DATE, $type);break;
			case LAMAZON_DATA_DELETING:
				AmazonStatus::setStatus($product, LAMAZON_DATA_DELETED, $type); break;
			default:break;
		}
	}


	public static function setProductStatus($product_instance,$status, $isVariation = false){
		AmazonStatus::setStatus($product_instance, $status, $isVariation?"Variation":"Product");
	}


	public static function setProductUpdating($product_instance, $isVariation = false){
		AmazonStatus::setUpdating($product_instance, $isVariation?"Variation":"Product");
	}


	public static function setRelationStatus($product_instance,$status){
		AmazonStatus::setStatus($product_instance, $status, "Relation");
	}

	public static function setRelationUpdating($product_instance){
		AmazonStatus::setUpdating($product_instance, "Relation");
	}


	public static function setPriceStatus($product_instance, $status){
		$product_instance->amazon_status = Tools::replaceCharAt($product_instance->amazon_status, 2, $status."");
	}

	public static function setPriceUpdating($product_instance){
		AmazonStatus::setUpdating($product_instance, "Price");
	}

	public static function setPriceUpdated($product_instance){
		AmazonStatus::setUpdated($product_instance, "Price");
	}


	public static function setInventoryStatus($product_instance, $status){
		AmazonStatus::setStatus($product_instance, $status, "Inventory");
	}

	public static function setInventoryUpdating($product_instance){
		AmazonStatus::setUpdating($product_instance, "Inventory");
	}
	public static function setInventoryUpdated($product_instance){
		AmazonStatus::setUpdated($product_instance, "Inventory");
	}

	public static function setImageStatus($product_instance, $status){
		$product_instance->amazon_status = Tools::replaceCharAt($product_instance->amazon_status, 4, $status."");
	}

	//判断用户当前是否可对商品进行编辑行为: 在商品本体， 商品为父体的变体中的任何一个，存在有各项数据的任何一个上传行为，商品，以及其对应变体不可编辑
	public static function changableProduct($product){

		if(AmazonStatus::isUpdating($product,"Proudct")) return false;
		if(AmazonStatus::isUpdating($product,"Relation")) return false;
		if(AmazonStatus::isUpdating($product,"Price"))   return false;
		if(AmazonStatus::isUpdating($product,"Inventory")) return false;
		if(AmazonStatus::isUpdating($product,"Image")) return false;

		$ret = true;
		$variations = Variation::find(array(
			"product_id = :p_id:",
			"bind"=>array("p_id"=>$product->id)
		));

		foreach ($variations as $id => $variation) {
			if(AmazonStatus::isUpdating($variation,"Variation")){
				$ret = false;
				break;
			}

			if(AmazonStatus::isUpdating($variation,"Price")){
				$ret = false;
				break;
			}

			if(AmazonStatus::isUpdating($variation,"Inventory")){
				$ret = false;
				break;
			}

			if(AmazonStatus::isUpdating($variation,"Image")){
				$ret = false;
				break;
			}
		}

		return $ret;
	}

}