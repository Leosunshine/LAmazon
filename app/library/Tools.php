<?php

class Tools
{
	public static function getGUID(){
		if (function_exists('com_create_guid')){
	        return com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	            .substr($charid, 0, 8).$hyphen
	            .substr($charid, 8, 4).$hyphen
	            .substr($charid,12, 4).$hyphen
	            .substr($charid,16, 4).$hyphen
	            .substr($charid,20,12)
	            .chr(125);// "}"
	       return $uuid;
	   }
	}

	public static function removeDir($dir){
		$dir = trim($dir,"/");
		$files = scandir($dir);
		foreach ($files as $key => $file) {
			if("." === $file || ".." === $file) continue;
			if(file_exists($dir."/".$file)){
				unlink($dir."/".$file);
			}
		}
		rmdir($dir);
	}

	//获取商品的上传准备状态
	public static function isUpdatePrepared_Product($status){
		$ret = substr($status, 0, 1);

		switch ($ret) {
			case '6':	$ret = -1;break;   //状态-1表示进行删除操作
			case '0':
			case '2':
			case '5':	$ret =  0;break; 	//状态为0, 表示不进行任何操作

			case '1':   $ret = 2; break;    //状态为2, 表示进行初始化上传
			case '3':	$ret = 1; break;    //状态为1, 表示进行上传操作

			case '4':
			default:	$ret = -2; break;   //状态为-2, 表示当前有上传操作进行中
		}

		return $ret;
		
	}

	public static function isUpdatePrepared_RelationShip($status){
		$ret = substr($status, 0, 1);
		//只有当产品信息与服务器保持同步时，才可进行其他类别的上传(SKU有效且真实)
		if ('2' !== $ret && '5' !== $ret) {
			return 0;
		}

		$ret = substr($status, 1,1);
		switch ($ret) {
			case '0':
			case '2':
			case '5':	$ret = 0; break;

			case '1':
			case '3':	$ret = 1; break;

			case '4':
			default:	$ret = -2; break;
		}

		return $ret;
	}

	public static function isUpdatePrepared_Price($status){
		$ret = substr($status, 0, 1);
		if ('2' !== $ret && '5' !== $ret) {
			return 0;
		}

		$ret = substr($status, 2,1);
		switch ($ret) {
			case '0':
			case '2':
			case '5':	$ret = 0; break;

			case '1':
			case '3':	$ret = 1; break;

			case '4':
			default:	$ret = -2; break;
		}

		return $ret;
	}

	public static function isUpdatePrepared_Inventory($status){
		$ret = substr($status, 0, 1);
		if ('2' !== $ret && '5' !== $ret) {
			return 0;
		}

		$ret = substr($status, 2,1);
		switch ($ret) {
			case '0':
			case '2':
			case '5':	$ret = 0; break;

			case '1':
			case '3':	$ret = 1; break;

			case '4':
			default:	$ret = -2; break;
		}

		return $ret;
	}

	public static function countOfPreparedProduct($products){
		$result = array("add"=>0, "update"=>0, "delete"=>0);

		foreach ($products as $index => $product) {
			$amazon_status = $product["amazon_status"];
			$product_status = Tools::isUpdatePrepared_Product($amazon_status);
			switch ($product_status) {
				case  1: $result["update"]++;break;
				case -1: $result["delete"]++;break;
				case  2: $result["add"]++;break;
			}

			$variations = Variation::find(array(
				"product_id = :p_id:",
				"bind"=>array("p_id"=>$product["id"])
			))->toArray();

			if(!$variations) continue;
			foreach ($variations as $key => $variation) {
				$va_status = $variation['amazon_status'];
				$va_status = Tools::isUpdatePrepared_Product($va_status);
				switch ($va_status) {
					case  1: $result["update"]++;break;
					case -1: $result["delete"]++;break;
					case  2: $result["add"]++;break;
				}
			}
		}
		return $result;
	} 

	public static function replaceCharAt($string, $pos, $char){
		$chars = str_split($string);
		$chars[$pos] = $char;
		return implode("", $chars);
	}
}