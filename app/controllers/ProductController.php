<?php

/**
* 
*/

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Mvc\Model\Manager as Manager;
use Phalcon\Mvc\Model\Query as Query;

class ProductController extends ControllerBase
{
	public function initialize(){
		#此处给出验证用户的代码
		parent::initialize();
		$this->view->setTemplateAfter("baseproduct");
		$seller = $this->session->get("userInfo");
		$this->view->setVar("username",$seller['username']);
		// include_once("../app/library/LAmazonConfig.php");
		// if(!$seller){
		// 	$seller = array(
		// 		"sellerId" => $amazon_config["MERCHANT_ID"],
		// 		"token"	   => $amazon_config["token"]
		// 	);
		// 	$this->session->set("sellerInfo",$seller);
		// }
	}
	public function indexAction(){
		//初始化适用对象列表
		$design_fors = DesignFor::find();
		$design_for_options = "";
		foreach ($design_fors as $key => $value) {
			$design_for_options = $design_for_options."<option>$value->name_en - $value->name_cn</option>";
		}
		$this->view->setVar("design_for_options",$design_for_options);

		//初始化金属类型对象列表
		$metal_types = MatelTypes::find();
		$metal_type_options = "";
		foreach ($metal_types as $key => $value) {
			$metal_type_options = $metal_type_options."<option>$value->name_en - $value->name_cn</option>";
		}
		$this->view->setVar("metal_type_options",$metal_type_options);

		//初始化产品材料类型对象列表
		$material_types = Materials::find();
		$material_type_options = "";
		foreach ($material_types as $key => $value) {
			$material_type_options = $material_type_options."<option>$value->name_en - $value->name_cn</option>";
		}
		$this->view->setVar("material_type_options",$material_type_options);

		//初始化珠宝类型对象列表
		$jewel_types = JewelTypes::find();
		$jewel_type_options = "";
		foreach ($jewel_types as $key => $value) {
			$jewel_type_options = $jewel_type_options."<option>$value->name_en - $value->name_cn</option>";
		}
		$this->view->setVar("jewel_type_options",$jewel_type_options);

		//初始化供应商类型对象列表
		$suppliers = Suppliers::find();
		$suppliers_options = "";
		foreach ($suppliers as $key => $value) {
			$suppliers_options .= "<option value='$value->id'>$value->name</option>";
		}
		$this->view->setVar("suppliers_options",$suppliers_options);

		//初始化现有用户列表
		$users = Users::find();
		$users_options = "";
		foreach ($users as $key => $value) {
			$users_options.="<option value='$value->id'>$value->username</option>";
		}
		$this->view->setVar("users_options",$users_options);

		//初始化产品分类列表
		// $category = Amazoncategory::find(array(
		// 		"level = :level:",
		// 		"bind"=>array("level"=>1)
		// 	));
		// $first_level_options = "";
		// foreach ($category as $key => $value) {
		// 	$first_level_options .= "<option value='$value->id'>$value->name_en</option>";
		// }
		// $this->view->setVar("first_level_options",$first_level_options);

		//初始化戒指尺寸列表
		$ringsize_options = "<option>Adjustable</options>";
		for($i = 0; $i < 32.5; $i+= 0.5){
			$ringsize_options.="<option>$i</option>";
		}
		$this->view->setVar("ringsize_options",$ringsize_options);
	}
	
	public function updateProductsFromAmazonAction(){
		$this->view->disable();
		$seller = $this->session->get("sellerInfo");
		include("../app/library/LAmazonConfig.php");
		if(!$seller){
			$seller = array(
				"sellerId" => $amazon_config["MERCHANT_ID"],
				"token"	   => $amazon_config["token"]
			);
		}
		$sellerId = $seller['sellerId'];
		$token = $seller['token'];
		$serviceUrl = $amazon_config['ServiceUrlProduct'];
		$config = array (
			'ServiceURL' => $serviceUrl,
			'ProxyHost' => null,
			'ProxyPort' => -1,
			'ProxyUsername' => null,
			'ProxyPassword' => null,
			'MaxErrorRetry' => 3
		);

		$service =new MarketplaceWebServiceProducts_Client(
			$amazon_config['AWS_ACCESS_KEY_ID'],
		    $amazon_config['AWS_SECRET_ACCESS_KEY'],
		    $amazon_config['APPLICATION_NAME'],
  		   	$amazon_config['APPLICATION_VERSION'],
  		  	$config
		);

		$content = file_get_contents("products.txt");
		echo "<pre/>";
		$lines = explode("\n", $content);
		$asinListArray = array();
		$index = 0;
		$SKUByASIN = array();

		for($i = 200; $i < (count($lines) +10);$i+=10){
			$tempList = new MarketplaceWebServiceProducts_Model_ASINListType();
			$tempArray = array();
			for($j = 0; $j < 10; $j++){
				if(($i + $j) >= count($lines)) break;
				$elements = explode("\t", $lines[$i + $j]);
				$tempArray[] = $elements[1];
				$SKUByASIN[$elements[1]] = $elements[0];
			}
			$tempList->setASIN($tempArray);
			$asinListArray[] = $tempList;
		}


		foreach ($asinListArray as $key => $list) {
			$request = new MarketplaceWebServiceProducts_Model_GetMatchingProductRequest();
			$request->setSellerId($sellerId);
			$request->setMWSAuthToken($token);
			$request->setMarketplaceId($marketplace_id["Germany"]["MarketplaceId"]);
			$request->setASINList($list);

			$response = $service->GetMatchingProduct($request);
			$response = $response->toXML();
			$response = preg_replace("/[^http|^xml]\:/", "_", $response);
			$xml = simplexml_load_string($response);
			$xml = json_encode($xml);
			$json_data = json_decode($xml,true);
			$products = $json_data["GetMatchingProductResult"];
			$fields = array();
			$dimension = array();
			foreach ($products as $index => $product) {
				$productInstance = Products::findFirst(array(
					"ASIN=:str:",
					'bind'=>array('str'=>$product["@attributes"]["ASIN"])
				));

				if(!$productInstance) $productInstance = new Products();
				$productInstance->ASIN = $product["@attributes"]["ASIN"];
				$productInstance->title = $product["Product"]["AttributeSets"]["ns_ItemAttributes"]["ns_Title"] || "no title";

				$sets = $product["Product"]["AttributeSets"]["ns_ItemAttributes"];

				foreach ($sets as $tagValue => $value) {
					$tag = preg_replace("/ns_/", "", $tagValue);
					if(!in_array($tag, $fields)) $fields[] = $tag;
				}
				if(isset($sets["ns_ItemDimensions"])){
					$dimensionSets = $sets["ns_ItemDimensions"];
					foreach ($dimensionSets as $tagValue => $value) {
						$tag = preg_replace("/ns_/", "", $tagValue);
						if(!in_array($tag, $dimension)) $dimension[] = $tag;
					}

					$productInstance->weight = isset($sets["ns_ItemDimensions"]["ns_Weight"])?isset($sets["ns_ItemDimensions"]["ns_Weight"]):"unknown";
				}

				if(isset($sets["ns_SmallImage"])){
					$imgUrl = $sets["ns_SmallImage"];
					foreach ($imgUrl as $index => $iurl) {
						$productInstance->img = $imgUrl["ns_URL"];
					}
				}
				
				// $productInstance->brand = isset($sets["ns_Brand"])?$sets["ns_Brand"]:"unknown";
				// $productInstance->label = isset($sets["ns_Label"])?$sets["ns_Label"]:"unknown";
				// $productInstance->manufacturer = isset($sets["ns_Manufacturer"])?$sets["ns_Manufacturer"]:"unknown";
				// $productInstance->number_of_items = isset($sets["ns_NumberOfItems"])?$sets["ns_NumberOfItems"]:1;
				// $productInstance->package_quantity = isset($sets["ns_PackageQuantity"])?$sets["ns_PackageQuantity"]:1;
				// $productInstance->part_number = isset($sets["ns_PartNumber"])?$sets["ns_PartNumber"]:"unknown";
				// $productInstance->product_group = isset($sets["ns_ProductGroup"])?$sets["ns_ProductGroup"]:"unknown";
				// $productInstance->product_type_name = isset($sets["ns_ProductTypeName"])?$sets["ns_ProductTypeName"]:"unknown";
				// $productInstance->publisher = isset($sets["ns_Publisher"])?$sets["ns_Publisher"]:"unknown";
				// $productInstance->studio = isset($sets["ns_Studio"])?$sets["ns_Studio"]:"unknown";
				// $productInstance->color  = isset($sets["ns_Color"])?$sets["ns_Color"]:"unknown";
				// $productInstance->binding = isset($sets["ns_Binding"])?$sets["ns_Binding"]:"unknown";
				// $productInstance->is_adult_product = isset($sets["ns_IsAdultProduct"])?$sets["ns_IsAdultProduct"]:"false";
				// $productInstance->is_memorabilia = isset($sets["ns_IsMemorabilia"])?$sets["ns_IsMemorabilia"]:"false";
				// $productInstance->material_type  = isset($sets["ns_MaterialType"])?$sets["ns_MaterialType"]:"unknown";
				$productInstance->save();
			}
		}

		print_r($fields);
		print_r($dimension);
		
		
		

		// foreach ($response as $key => $value) {
		// 	$productInstance = array();
		// 	$product = $value->getProduct();
		// 	$identifiers = $product->getIdentifiers();
		// 		$asinValue = $identifiers->getMarketplaceASIN()->getASIN();
			
		// 	$productInstance['asin'] = $asinValue;
		// 	$productInstance['sku']  = $SKUByASIN[$asinValue];

		// 	$attributeSets = $product->getAttributeSets()->getAny();
		// 		$nodeValue = $attributeSets[0]->nodeValue;
		// 		$details = explode("http", $nodeValue);
		// 		$detail = $details[0];
		// 		$img = explode("jpg", $details[1])[0];
		// 		$img = "http$img"."jpg";
		// 		$title = explode("jpg", $nodeValue)[1];

		// 	print_r($attributeSets);
		// 		$resource = fopen("imgCache/".$asinValue.".jpg", 'wb');
		// 		$ch = curl_init($img);
		// 		curl_setopt($ch, CURLOPT_FILE, $resource);
		// 		curl_setopt($ch, CURLOPT_HEADER, 0);
		// 		$output = curl_exec($ch);
		// 		curl_close($ch);


		// 	//$relationships = $product->getRelationShips();

		// 	//$competitivePring = $product->getCompetitivePricing();

		// 	//$salesRanking = $product->getSalesRankings();

		// 	//$lowestOfferListing = $product->getLowestOfferListings();

		// 	//$offers = $product->getOffers();
		// }
	}

	public function updateProductAction(){
		$this->view->disable();
		$product = json_decode($this->request->getPost("product"),true);
		$log = new LogRecoder("./temp/operation.txt");
		$product_id = $this->request->getPost("id");
		if($product_id === "0"){
			$product_id = $this->updateProduct($product,"0");
			$log->add("A product is created: $product_id");
			$this->dataReturn(array("success"=>$product_id));
		}else{
			$this->updateProduct($product,$product_id);
			$log->add("A product is modified: $product_id");
			$this->dataReturn(array("success"=>$product_id));
		}
	}

	public function truncateDatabaseAction(){
		$this->view->disable();
		$this->db->query("truncate table products");
		$this->db->query("truncate table image_urls");
		$this->db->query("truncate table variation");
		//关于清空图像文件，暂时不做处理，需要手动删除，不删除也不会影响系统运行，但是浪费系统空间
		$this->dataReturn(array("success"=>"success"));
	}

	public function updateProduct($product,$product_id){
		$manager = new TxManager();
		$transaction = $manager->get();
		$isNewProduct = $product_id === "0";

		$isProductInfoEdit = true;
		$isPriceEdited = true;
		$isInventoryEdited = true;

		if(!$isNewProduct){
			$product_instance = Products::findFirst(($product_id*1));
			$isProductInfoEdit = Tools::isProductInfoEdit($product,$product_instance);
		}else{
			$product_instance = new Products();
			$product_instance->amazon_status = "10111";
			$product_instance->status = 1;
			try{
				$product_instance->save();
				$product_instance = Products::findFirst($product_instance->id);
				$product_id = $product_instance->id;

			}catch(TxFailed $e){
				$transaction->rollback();
			}
		}
		$product_instance->setTransaction($transaction);

		//首先确保文件夹存在
		$image_fold = "./img/".$product_instance->id;
		if(!is_dir($image_fold)){
			mkdir($image_fold);
		}
		//处理图片,查找文件guid验证文件是否还有效
		$images = $product['images'];
		$image_map = array();
		$images_field = "";
		$main_image_id = 0;

		foreach ($images as $guid => $filename) {
			$image_url = ImageUrls::findFirst(array(
				"guid = :guid:",
				"bind"=>array("guid"=>$guid)
			));
			if(!$image_url){
				//新增图片
				$image_url = ImageUrls::findFirst(array(
					"state = :state:",
					"bind"=>array("state"=>0)
				));
				if(!$image_url) $image_url = new ImageUrls();
				$url = $image_fold."/".$guid."_".$filename;
				rename("./img/".$guid."_".$filename, $url);
				$image_url->setTransaction($transaction);
				$image_url->guid = $guid;
				$image_url->file_name = $filename;
				$image_url->url = $url;
				$image_url->state = 1;
				try{
					$image_url->save();
				}catch(TxFailed $e){
					$transaction->rollback();
				}
			}

			$images_field.="$image_url->id|";
			$image_map[$guid] = $image_url->id;
		}
		$product_instance->images = trim($images_field,"|");
		$main_image_id = explode("|",trim($images_field, "|"))[0] * 1;
		$product_instance->main_image_id = $main_image_id;

		//为了得到上传的标识位，应该先处理变体
		//处理变体. 变体要用SKU控制, 一旦修改SKU，等效于重新添加变体

		//取出原有变体集合
		$variations = Variation::find(array(
			"product_id = :product_id:",
			"bind"=>array("product_id"=>$product_id)
		));

		//建立原有SKU与变体的映射
		$variation_map = array();
		foreach($variations as $key => $variation){
			$variation_map[$variation->SKU] = $variation;
			$variation->setTransaction($transaction);
		}

		$variations = $product['variations'];
		$variation_field = "";

		foreach ($variations as $index => $variation) {
			$variation_instance = $this->createVariation($variation,$image_map,$variation_map, $isProductInfoEdit);
			$key = array_search($variation_instance, $variation_map);
			if($key){
				unset($variation_map[$key]);
			}
			$variation_instance->setTransaction($transaction);
			$variation_instance->product_id = $product_instance->id;
			try {
				$variation_instance->save();
				$variation_field .= "$variation_instance->id|";
			} catch (Exception $e) {
				$transaction->rollback();
			}
		}

		foreach ($variation_map as $key => $value) {
			//余下各变体删除
			try{
				if(!AmazonStatus::isAmazonExist($value)){
					$value->product_id = 0;//原先服务器中就不存在的,直接置无效
				}else{
					//待删除变体置位为6
					AmazonStatus::setStatus($product, LAMAZON_READY_TO_DELETE, "Variation");
				}
				$value->save();
			} catch(Exception $e){
				$transaction->rollback();
			}
		}

		$product_instance->variation_node = trim($variation_field,"|");

		//再根据新的商品中有无变体,处理其标识位
		if($product_instance->variation_node == ""){
			if($product_instance->price != $product["Price"]){
				AmazonStatus::setNeedUpdate($product_instance,"Price");
				$product_instance->price = $product["Price"];
			}

			if($product_instance->product_count != $product["product_count"]){
				AmazonStatus::setNeedUpdate($product_instance,"Inventory");
				$product_instance->product_count = $product["product_count"];
			}
			AmazonStatus::setInvalid($product_instance,"Price");
			AmazonStatus::setInvalid($product_instance,"Inventory");
		}else{
			//有变体,价格库存位置无效
			AmazonStatus::setInvalid($product_instance,"Price");
			AmazonStatus::setInvalid($product_instance,"Inventory");
			//只要商品有变体，则需要更新变体关系
			AmazonStatus::setNeedUpdate($product_instance,"Relation");
		}

		//设定商品上传标识位
		if($isProductInfoEdit){
			AmazonStatus::setNeedUpdate($product_instance,"Product");
		}

		//同步各字段
		foreach ($product as $key => $value) {
			switch ($key) {
				case 'variations':
				case 'images':
				case 'price':
				case 'currency':
				case 'product_count':break;
				default:
					if($product_instance->$key != $value){
						$product_instance->$key = $value;
					}
					break;
			}
		}


		$SKU = $product["SKU"];
		$EAN = $product["ASIN"];

		$product_instance->SKU = $SKU?$SKU:AmazonAPI::composeSKU();
		$product_instance->ASIN = $EAN?$EAN:AmazonAPI::composeEAN();

		try {
			$product_instance->save();
		} catch (Exception $e) {
			$transaction->rollback();
		}
		$transaction->commit();
		return $product_instance->id;
	}

	public function deleteProductAction(){
		$this->view->disable();
		$manager = new TxManager();
		$transaction = $manager->get();
		$product_id = $this->request->getPost("id");
		$product_instance = Products::findFirst($product_id);
		$product_instance->setTransaction($transaction);

		$amazon_status = $product_instance->amazon_status;
		$amazon_status = substr($amazon_status, 0, 1);
		if("1" === $amazon_status){
			//不删除任何记录,便于追溯
			$product_instance->status = 7;
		}else{
			$product_instance->status = 6;
			$product_instance->amazon_status = Tools::replaceCharAt($product_instance->amazon_status,0,"6");
			$variations = Variation::find(array(
				"product_id = :p_id:",
				"bind"=>array("p_id"=>$product_id)
			));

			foreach ($variations as $key => $variation) {
				$variation->setTransaction($transaction);
				$variation->amazon_status = Tools::replaceCharAt($variation->amazon_status, 0, "6");
				$variation->save();
			}
		}
		$product_instance->save();
		$transaction->commit();

		$this->dataReturn(array("success"=>$product_id));
	}
	public function deleteProductOpeartionAction(){
		$this->view->disable();

		$manager = new TxManager();
		$transaction = $manager->get();
		$product_id = $this->request->getPost("id");
		$product_instance = Products::findFirst($product_id);

		//清除变体条目,将变体条目的商品id置为0
		$variations = Variation::find(array(
			"product_id = :product_id:",
			"bind"=>array("product_id"=>$product_id)
		));

		foreach ($variations as $index => $variation) {
			$variation->setTransaction($transaction);
			$variation->product_id = 0;

			try {	$variation->save();	} catch (Exception $e) {$transaction->rollback();}
		}

		//清除与商品有关的图片条目及其文件，清除条目方式为状态置0
		$images = $product_instance->images;
		if(preg_match("/\S+|/", $images)){
			$images = explode("|", substr($images, 0,-1));
			foreach ($images as $index => $image) {
				$image_instance = ImageUrls::findFirst($image);
				$image_instance->setTransaction($transaction);
				$image_instance->state = 0;

				if (file_exists($image_instance->url)) {
				 	unlink($image_instance->url);
				}
				try {	$image_instance->save();	} catch (Exception $e) {	$transaction->rollback();	}
			}
		}
		
		Tools::removeDir("./img/".$product_id);

		$product_instance->delete();

		$transaction->commit();		
		$this->dataReturn(array("success"=>$images[count($images) - 1]));
	}
	private function createVariation($variation,$image_map,$variation_map,$isProductInfoEdit = true){
		$SKU = $variation['SKU'];
		$amazon_pro_status = "0";

		if($SKU && $variation_map[$SKU]){
			//已存在变体,正在修改
			$variation_instance = $variation_map[$SKU];
			//根据本体是否修改，定义变体是否修改
			if($isProductInfoEdit){	AmazonStatus::setNeedUpdate($variation_instance,"Variation");}
		}else{
			//变体不存在,需要新建
			$variation_instance = Variation::findFirst(array(
				"product_id = 0"
			));
			if(!$variation_instance) $variation_instance = new Variation();
			$variation_instance->amazon_status = "10111";
		}

		//更新库存
		if($variation["inventory_count"] * 1 != $variation_instance->inventory_count){
			$variation_instance->inventory_count = $variation["inventory_count"] * 1;
			AmazonStatus::setNeedUpdate($variation_instance,"Inventory");
		}

		//更新价格(此处应该保存加价还是价格有待商榷)
		if($variation["price_bonus"] * 1 != $variation_instance->price_bonus){
			$variation_instance->price_bonus = $variation["price_bonus"] * 1;
			AmazonStatus::setNeedUpdate($variation_instance,"Price");
		}
		//更新名称
		if($variation["name"] != $variation_instance->name){
			$variation_instance->name = $variation["name"];
			AmazonStatus::setNeedUpdate($variation_instance,"Variation");
		}

		//主要针对没有初始化的商品,故不需要进行修改判断
		$variation_instance->SKU = $variation['SKU']?$variation['SKU']:AmazonAPI::composeSKU();
		$variation_instance->EAN = $variation['EAN']?$variation['EAN']:AmazonAPI::composeEAN();
		

		$variation_instance->images = "";
		$images = $variation['images'];
		foreach ($images as $index => $image) {
			$variation_instance->images .= "$image_map[$image]|";
		}
		$variation_instance->images = trim($variation_instance->images,"|");
		return $variation_instance;
	}

	public function updateSupplierAction(){
		$this->view->disable();
		$oper = $this->request->getPost('oper');
		$name = $this->request->getPost('name');
		$QQ = $this->request->getPost("QQ");
		$tel = $this->request->getPost("tel");
		$remark = $this->request->getPost("remark");
		$id = $this->request->getPost("id");

		if($oper === "add"){
			$supplier_instance = new Suppliers();
			$supplier_instance->name = $name;
			$supplier_instance->QQ = $QQ;
			$supplier_instance->tel = $tel;
			$supplier_instance->remark = $remark;

			$supplier_instance->create();

			$this->dataReturn(array('success'=>$supplier_instance->id));
		}else if($oper === "edit"){
			$supplier_instance = Suppliers::findFirst($id);
			if($supplier_instance){
				$supplier_instance->name = $name;
				$supplier_instance->QQ = $QQ;
				$supplier_instance->tel = $tel;
				$supplier_instance->remark = $remark;
				$supplier_instance->save();
				$this->dataReturn(array('success'=>'success'));
			}else{
				$this->dataReturn(array('error'=>'the target supplier can not be found in database!'));
			}
		}else if($oper === "del"){
			$manager = new TxManager();
			$transaction = $manager->get();
			$ids = explode(",", $id);
			foreach ($ids as $key => $value) {
				$product_instance = Products::find(array(
					"supplier_id = :supplier_id:",
					"bind"=>array("supplier_id"=>$value)
				));

				foreach ($product_instance as $index => $product) {
					$product->setTransaction($transaction);
					$product->supplier_id = 0;
					$product->save();
				}

				$supplier_instance = Suppliers::findFirst($value);
				if($supplier_instance){
					$supplier_instance->setTransaction($transaction);
					$supplier_instance->delete();
				}

				$transaction->commit();
			}
		}
	}

	public function localcategoryeditAction(){

	}

	public function creatlocalcategoryAction(){
		$this->view->disable();
		$parent_id = $this->request->getPost("parent_id");
		$child_name = $this->request->getPost("child_name");
		$amazon_id = $this->request->getPost("amazon_id");
		$amazon_node_path = $this->request->getPost("amazon_node_path");
		$amazon_nodeId = $this->request->getPost("amazon_nodeId");


		if(!$parent_id || !$child_name || !$amazon_id){
			$this->dataReturn(array("error"=>"wrong parameters"));
			return;
		}
		//保证数据类型
		$parent_id *= 1;
		$amazon_id *= 1;

		//查询其父
		$parent = Localcategory::findFirst($parent_id);
		if(!$parent){
			$this->dataReturn(array("error"=>"the father node is not found!"));
			return;
		}

		$localCategory = new Localcategory();
		$localCategory->name = $child_name;
		$localCategory->level = $parent->level + 1; //为其父的下一级
		$localCategory->is_end_point = 1;
		$localCategory->parent_id = $parent_id;
		$localCategory->amazon_category_id = $amazon_id;
		$localCategory->amazon_node_path = $amazon_node_path;
		$localCategory->amazon_nodeId = $amazon_nodeId;

		$amazonCategory = Amazoncategory::findFirst($amazon_id)->toArray();
		$localCategory->variation_theme = $amazonCategory['variation_theme'];
		$localCategory->create();

		if($parent->is_end_point === 1){
			$parent->is_end_point = 0;
			$parent->save();
		}

		$now = Localcategory::find()->toArray();
		$this->dataReturn(array("success"=>"saved", "now"=>$now));
	}

	public function modifylocalcategoryAction(){
		$this->view->disable();
		$targetId = $this->request->getPost("targetId");
		$name = $this->request->getPost("child_name");
		$amazon_id = $this->request->getPost("amazon_id");
		$amazon_node_path = $this->request->getPost("amazon_node_path");
		$amazon_nodeId = $this->request->getPost("amazon_nodeId");

		if(!$targetId || !$name || !$amazon_id){
			$this->dataReturn(array("error"=>"wrong parameters"));
			return;
		}

		$targetId *= 1; $amazon_id *= 1;
		$target = Localcategory::findFirst($targetId);
		if(!$target){
			$this->dataReturn(array("error"=>"target not found"));
			return;
		}

		$target->name = $name;
		$target->amazon_category_id = $amazon_id;
		$target->amazon_node_path = $amazon_node_path;
		$target->amazon_nodeId = $amazon_nodeId;

		$amazonCategory = Amazoncategory::findFirst($amazon_id)->toArray();
		$target->variation_theme = $amazonCategory['variation_theme'];

		$target->save();

		$now = localCategory::find()->toArray();
		$this->dataReturn(array("success"=>"saved","now"=>$now));
	}

	public function truncateLocalCategoryAction(){
		$this->view->disable();
		$this->db->query("truncate table localcategory");
		$this->db->query("INSERT INTO localcategory(id, name, remark, level, is_end_point, parent_id, amazon_category_id)VALUES(1, 'root', '根', 0, 0, 0, 0);");
		$now = Localcategory::find()->toArray();
		$this->dataReturn(array("success"=>"success","now"=>$now));
	}

	public function renamelocalcategoryAction(){
		$this->view->disable();
		$targetId = $this->request->getPost("targetId");
		$newName = $this->request->getPost("newName");

		if(!$targetId || !$newName){
			$this->dataReturn(array("error"=>"参数错误"));
			return;
		}

		$category = Localcategory::findFirst($targetId);
		$category->name = $newName;
		$category->save();
		$this->dataReturn(array("success"=>"success"));
	}

	public function deletelocalcategoryAction(){
		$this->view->disable();
		$ids = $this->request->getPost("ids");
		if(!$ids) $this->dataReturn(array("error"=>"参数错误"));
		
		$ids = str_replace("|", " or id = ", $ids);
		$ids = "id = ".$ids;

		$categories = Localcategory::find($ids);
		$categories->delete();

		$this->dataReturn(array("success"=>"success"));
	}
}