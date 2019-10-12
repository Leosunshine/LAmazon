<?php

class AmazonXSDFactory{
	
	static public function constructHome_Home($product, $isVariation = false, $va_name = ""){
		$VariationData = array();
		if(!$isVariation){
			$VariationData["VariationTheme"] = $product["variation_theme"];
		}else{
			$VariationData[$product["variation_theme"]] = $va_name;
		}

		$xml = array(
			"ProductType" => array(
				"Home"=>array(
					//"Material"=>$product['material_type']?$product['material_type']:"unknown"
					"Material"=>"Lamazon"
				)
			),
			"Parentage" => $isVariation? "child":"parent",
			"VariationData"=>$VariationData,
			"IdentityPackageType"=>"bulk"
		);
		return array("Home"=>$xml);
	}

	static public function constructHome_BedAndBath($product){
		$xml = array(
			"ProductType" => array(
				"BedAndBath" => array(
					"IdentityPackageType"=>"bulk",
					//"Material"=>$product['material_type']?$product['material_type']:"unknown",
					"Material"=>"Lamazon",
					"VariationData"=>array(
						"VariationTheme"=>$product["variation_theme"]
					)
				)
			),
			"Parentage" => "parent"
		);

		return array("Home"=>$xml);
	}

	static public function constructHome_FurnitureAndDecor($product){
		$xml = array(
			"ProductType" => array(
				"FurnitureAndDecor" => array(
					"IdentityPackageType" => "bulk",
					//"Material"=>$product['material_type']?$product['material_type']:"unknown",
					"Material"=>"Lamazon",
					"VariationData"=>array(
						"VariationTheme"=>$product["variation_theme"]
					)
				)
			),
			"Parentage" => "parent"
		);

		return array("Home"=>$xml);
	}

	static public function construct($product, $isVariation, $va_name){
		switch($product['amazon_category_id']){
			case 230: $ret = AmazonXSDFactory::constructHome_BedAndBath($product);break;
			case 231: $ret = AmazonXSDFactory::constructHome_FurnitureAndDecor($product);break;
			default: $ret = AmazonXSDFactory::constructHome_Home($product, $isVariation, $va_name);break;
		}

		return $ret;
	}
}