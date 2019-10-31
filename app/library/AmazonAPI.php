<?php
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
class AmazonAPI
{
	public static function updateProduct($products){
		$manager = new TxManager();
		$transaction = $manager->get();

		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;

		$uploadLog = array("submission_id"=>"","products"=>array(), "variations"=>array());

		foreach ($products as $index => $product) {
			$SKU = $product->SKU;
			$product->setTransaction($transaction);
			if(AmazonStatus::isNeedDelete($product)){
				//处理完全删除产品的操作，暂时不允许删除主产品来释放变体的操作
				$message[] = AmazonAPI::constructDeleteProductMessage($messageIndex++,$SKU);
				//修改删除状态为7
				AmazonStatus::setUpdating($product,"Product");
				$uploadLog["products"][$product->SKU] = array("id"=>$product->id,"SKU"=>$product->SKU,"operation"=>"delete");
				$product->save();

				//忽略变体状态，并所属该产品变体一律删除
				$variations = Variation::find(array(
					"product_id = :p_id:",
					"bind"=>array("p_id"=>$product->id)
				));
				foreach ($variations as $key => $variation) {
					$va_SKU = $variation->SKU;
					if(AmazonStatus::isAmazonExist($variation)){
						//若变体已存在amazon中
						$message[] = AmazonAPI::constructDeleteProductMessage($messageIndex++, $va_SKU);
						$uploadLog["variations"][$va_SKU] = array("id"=>$variation->id, "SKU"=>$va_SKU, "operation"=>"delete");
						AmazonStatus::setProductStatus($variation, LAMAZON_DATA_DELETING, true);
					}else{
						$variation->product_id = 0; //将该变体直接置无效
					}
					$variation->save();
				}
				continue;
			}

			$EAN = $product->ASIN;
			if(AmazonStatus::isNeedUpdate($product,"Product")){
				//需要更新product数据
				$message[] = AmazonAPI::constructUpdateProductMessage($messageIndex++, $SKU, $EAN, $product, false);
				AmazonStatus::setUpdating($product,"Product"); //设置上传中状态
				$uploadLog["products"][$SKU] = array("id"=>$product->id,"SKU"=>$SKU,"operation"=>"update");
				$product->save();
			}

			$variations = Variation::find(array(
				"product_id = :p_id:",
				"bind"=>array("p_id"=>$product->id)
			));

			$variation_theme = $product->variation_theme;

			foreach ($variations as $index => $variation) {
				$variation->setTransaction($transaction);
				$va_SKU = $variation->SKU;
				$va_name = $variation->name;
				$va_ASIN = $variation->EAN;

				if(AmazonStatus::isNeedDelete($variation)){
					//若该变体期望从amazon删除
					$message[] = AmazonAPI::constructDeleteProductMessage($messageIndex++, $va_SKU);
					$uploadLog["variations"][$va_SKU] = array("id"=>$variation->id, "SKU"=>$va_SKU, "operation"=>"delete");
				}
				else if(AmazonStatus::isNeedUpdate($variation,"Product")){
					$message[] = AmazonAPI::constructUpdateProductMessage($messageIndex++, $va_SKU, $va_ASIN, $product, true, $va_name);
					$uploadLog["variations"][$va_SKU] = array("id"=>$variation->id, "SKU"=>$va_SKU, "operation"=>"update");
				}
				AmazonStatus::setUpdating($variation, "Variation");
				$variation->save();
			}
		}

		$transaction->commit();
		return AmazonAPI::submitMessages($message, $amazon_config, "Product", array(
			"xml"=>"./temp/temp.dat",
			"uploadLog"=>$uploadLog
		));
	}

	public static function updatePrice($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;
		$uploadLog = array("products"=>array(),"variations"=>array());
		foreach ($products as $index => $product) {
			if(!AmazonStatus::isValid($product)) continue;
			$SKU = $product->SKU;
			$currency = $product->currency;
			$price = $product->price;
			switch ($currency) {
				case '美元': $currency = "USD";break;
				case '英磅': $currency = "GBP";break;
				case '欧元': $currency = "EUR";break;
				case '日元': $currency = "JPY";break;
				case '加元': $currency = "CAD";break;
				case '人民币': $currency = 'CNY';break;
				case '印度卢比': $currency = "INR";break;
				case '澳元': $currency = "AUD";break;
				case '雷亚尔': $currency = "BRL";break;
				case '墨西哥元': $currency = "MXN";break;
				case '土耳其里拉': $currency = "TRY";break;
				case '阿联酋迪拉姆': $currency = "AED";break;
				default:
					$currency = "DEFAULT";break;
			}

			$variations = $product->variation_node;

			if($variations){
				$variations = explode("|", $variations);

				foreach ($variations as $key => $value) {
					$variation_instance = Variation::findFirst($value);
					if(!(AmazonStatus::isValid($variation_instance, true) && AmazonStatus::isNeedUpdate($variation_instance,"Price")))	continue;

					$va_SKU = $variation_instance->SKU;
					$va_price_bonus = $variation_instance->price_bonus;
					$va_price = $price * 1.0 + $va_price_bonus * 1.0;
					$message[] = AmazonAPI::constructUpdatePriceMessage($messageIndex++, $va_SKU, $va_price, "EUR");
					AmazonStatus::setPriceUpdating($variation_instance);
					$uploadLog["variations"][$va_SKU] = array("id" => $variation_instance->id, "SKU"=>$va_SKU, "operation"=>"update_price");
					$variation_instance->save();
				}
			}else{
				if(!AmazonStatus::isNeedUpdate($product,"Price")) continue;
				$message[] = AmazonAPI::constructUpdatePriceMessage($messageIndex++, $SKU, $price, "EUR");
				AmazonStatus::setPriceUpdating($product);
				$uploadLog["products"][$SKU] = array("id"=>$product->id, "SKU"=>$SKU, "operation"=>"update_price");
				$product->save();
			}
			
		}
		return AmazonAPI::submitMessages($message, $amazon_config, "Price", array(
			"xml"=>"./temp/temp.dat",
			"uploadLog"=>$uploadLog
		));
	}
	
	public static function updateRelationship($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;
		$uploadLog = array("products"=>array(), "variations"=>array());
		foreach ($products as $index => $product) {
			if(!AmazonStatus::isNeedUpdate($product,"Relation")){
				continue;
			}

			$SKU = $product->SKU;
			$variations = $product->variation_node;
			$variations = explode("|", $variations);
			$relation = array();
			$uploadLog["products"][$SKU] = array("id" => $product->id,"SKU" => $SKU,"operation"=>"update_relation","variations"=>array());
			foreach ($variations as $key => $variation) {
				$variation_instance = Variation::findFirst($variation)->toArray();
				if(!$variation_instance) continue;

				$relation[] = array(
					"Relation"=>array(
						"SKU"=>$variation_instance["SKU"],
						"Type"=>"Variation"
					)
				);
				$uploadLog["products"][$SKU]["variations"][] = $variation_instance["SKU"];
			}

			$message[] = array(
				"Message"=>array(
					"MessageID"=>$messageIndex++,
					"OperationType"=>"Update",
					"Relationship"=>array(
						"ParentSKU"=>$SKU,
						$relation
					)
				)
			);

			AmazonStatus::setRelationUpdating($product);
			$product->save();
		}

		return AmazonAPI::submitMessages($message,$amazon_config,"Relation",array(
			"xml"=>"./temp/temp.dat",
			"uploadLog"=>$uploadLog
		));
		$feed_json = AmazonAPI::constructFeedJson($message, $amazon_config, "Relationship");
		if(!$feed_json) return 0;

		$feed = XMLTools::Json2Xml($feed_json);
		file_put_contents("./temp/temp.dat", $feed);
		$submission_id = AmazonAPI::submitFeed($feed,$amazon_config,"_POST_PRODUCT_RELATIONSHIP_DATA_");

		if(isset($submission_id["submission_id"])){
			return $submission_id;
		}

		$uploadLog["submission_id"] = $submission_id;
		$uploadLog["status"] = "submitted";
		file_put_contents("./temp/updateLogs/uploadLog.dat", json_encode($uploadLog, JSON_PRETTY_PRINT));
		return $submission_id;
	}
	public static function updateInventory($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;

		$uploadLog = array("products"=>array(),"variations"=>array());
		foreach ($products as $index => $product) {

			if(!AmazonStatus::isValid($product)) continue;

			$SKU = $product->SKU;
			$variations = $product->variation_node;
			if($variations){
				$variations = explode("|", $variations);
				foreach ($variations as $key => $value) {
					$variation_instance = Variation::findFirst($value);
					if(!(AmazonStatus::isValid($variation_instance, true) && AmazonStatus::isNeedUpdate($variation_instance,"Inventory")))	continue;
					$va_SKU = $variation_instance->SKU;
					$va_count = $variation_instance->inventory_count;
					$message[] = AmazonAPI::constructUpdateInventoryMessage($messageIndex++, $va_SKU, $va_count);
					AmazonStatus::setInventoryUpdating($variation_instance);
					$uploadLog["variations"][$va_SKU] = array("id"=>$variation_instance->id, "SKU"=>$va_SKU, "operation"=>"update_inventory");
					$variation_instance->save();
				}
			}else{
				if(!AmazonStatus::isNeedUpdate($product,"Inventory")) continue;
				$quantity = $product->product_count;
				$message[] = AmazonAPI::constructUpdateInventoryMessage($messageIndex++, $SKU, $quantity);
				AmazonStatus::setInventoryUpdating($product);
				$uploadLog["products"][$SKU] = array("id"=>$product->id, "SKU"=>$SKU, "operation"=>"update_inventory");
				$product->save();
			}
		}

		return AmazonAPI::submitMessages($message, $amazon_config, "Inventory", array(
			"xml"=>"./temp/temp.dat",
			"uploadLog"=>$uploadLog
		));
	}

	public static function updateShipping($products){
		$amazon_config = LAMAZONConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;

		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			$message[] = array(
				"Message"=>array(
					"MessageID" => ($index + 1),
					"OperationType"=>"Update",
					"Override"=>array(
						"SKU"=>$SKU,
						"ShippingOverride"=>array(
							"ShipOption"=>"Exp",
							"Type"=>"Additive",
							"ShipAmount"=>array(
								$product["fixed_shipping"],
								"__properties"=>array(
									"currency"=>"GBP"
								)
							)
						)
					)
				)
			);
		}

		$feed_json = array(
			"AmazonEnvelope" => array(
				"Header"=>array(
					"DocumentVersion" => 1.01,
					"MerchantIdentifier" => $amazon_config['MERCHANT_ID']
				),
				"MessageType"=>"Override",
				$message
			)			
		);

		$feed = XMLTools::Json2Xml($feed_json);
		file_put_contents("./temp/temp.dat", $feed);
		return AmazonAPI::submitFeed($feed,$amazon_config,"_POST_PRODUCT_OVERRIDES_DATA_");
	}
	public static function deleteAProduct($SKU){
		$feed = "<?xml version='1.0' encoding='utf-8'?>
			<AmazonEnvelope>
			  <Header>
			    <DocumentVersion>1.01</DocumentVersion>
			    <MerchantIdentifier>AB0EMHVN49K0J</MerchantIdentifier>
			  </Header>
			  <MessageType>Product</MessageType>
			  <PurgeAndReplace>false</PurgeAndReplace>
			  <Message>
			    <MessageID>1</MessageID>
			    <OperationType>Delete</OperationType>
			    <Product>
			      <SKU>$SKU</SKU>
			    </Product>
			  </Message>
			</AmazonEnvelope>";
		
		return AmazonAPI::submitFeed($feed,LAmazonConfig::$amazon_config);
	}

	public static function deleteProducts($products){
		$message = array();
		$messageIndex = 1;
		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			if($SKU === "LAMAZON-757D36DE") continue;
			$message[] = array(
				"Message"=>array(
					"MessageID"=>$messageIndex++,
					"OperationType"=>"Delete",
					"Product"=>array(
						"SKU"=>$SKU
					)
				)
			);
		}

		$feed_json = array(
			"AmazonEnvelope"=>array(
				"Header"=>array(
					"DocumentVersion"=>1.01,
					"MerchantIdentifier"=>LAmazonConfig::$amazon_config['MERCHANT_ID']
				),
				"MessageType"=>"Product",
				"PurgeAndReplace"=>"false",
				$message
			)
		);

		$feed = XMLTools::Json2Xml($feed_json);
		return AmazonAPI::submitFeed($feed,LAmazonConfig::$amazon_config);
	}

	public static function getSubmissionResult($submission_id){
		$amazon_config = LAmazonConfig::$amazon_config;
		$serviceUrl = $amazon_config['ServiceUrlSubmitDE'];
		$config = array (
			'ServiceURL' => $serviceUrl,
			'ProxyHost' => null,
			'ProxyPort' => -1,
			'MaxErrorRetry' => 3,
		);

		$service = new MarketplaceWebService_Client(
			$amazon_config['AWS_ACCESS_KEY_ID'],
			$amazon_config['AWS_SECRET_ACCESS_KEY'],
			$config,
			$amazon_config['APPLICATION_NAME'],
			$amazon_config['APPLICATION_VERSION']
		);

		$feedHandle = @fopen("php://memory", 'rw+');
		$parameters = array(
			'Merchant' => $amazon_config['MERCHANT_ID'],
			'FeedSubmissionId' => $submission_id,
			'FeedSubmissionResult' => $feedHandle,
			'MWSAuthToken' => $amazon_config['token']
		);

		$request = new MarketplaceWebService_Model_GetFeedSubmissionResultRequest($parameters);
		$response = $service->getFeedSubmissionResult($request);
		rewind($feedHandle);
		$result = stream_get_contents($feedHandle);
		return $result;
	}

	public static function composeSKU(){
		$SKU = Tools::getGUID();
		$SKU = substr($SKU, 28, 8);
		$SKU = "LAMAZON-".$SKU;
		return $SKU;
	}

	public static function composeEAN(){
		$pre = "982";
		$ean13 = rand(1,9999);											//随机厂商
		$ean13 = $pre.str_pad($ean13, 4,"0",STR_PAD_LEFT);				//借用毛里求斯的EAN
		$ean132 = rand(1,99999);										//随机产品编号
		$ean132 = str_pad($ean132,5,"0", STR_PAD_LEFT);
		$ean13 = $ean13.$ean132;
		$code = str_pad($ean13, 12, "0", STR_PAD_LEFT);
		$sum = 0;
		for($i=(strlen($code)-1);$i>=0;$i--){
			$sum += (($i % 2) * 2 + 1 ) * $code[$i];
		}
		$code = (10 - ($sum % 10));
		$ean13 .= $code;
		return $ean13;
	}

	public static function uploadImage($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;
		foreach ($products as $index => $product) {
			$main_image = $product['main_image_id'];
			$main_image = ImageUrls::findFirst($main_image)->toArray();
			$main_image_url = $main_image['url'];
			$main_image_url = "http://152.136.12.173".substr($main_image_url, 1);
			$SKU = $product['SKU'];
			
			$message[] = array(
				"Message"=>array(
					"MessageID"=>$messageIndex++,
					"OperationType"=>"Update",
					"ProductImage"=>array(
						"SKU"=>$SKU,
						"ImageType"=>"Main",
						"ImageLocation"=>$main_image_url
					)
				)
			);


			$images = $product['images'];
			$images = explode("|", $images);
			foreach ($images as $key => $image) {
				if($key >= 8) break;
				$image_array = ImageUrls::findFirst($image)->toArray();
				$image_url = $image_array['url'];
				$image_url = "http://152.136.12.173".substr($image_url, 1);
				$message[] = array(
					"Message"=>array(
						"MessageID"=>$messageIndex++,
						"OperationType"=>"Update",
						"ProductImage"=>array(
							"SKU"=>$SKU,
							"ImageType"=>"PT".($key+1),
							"ImageLocation"=>$image_url
						)
					)
				);
			}

			$feed_json = array(
				"AmazonEnvelope"=>array(
					"Header"=>array(
						"DocumentVersion"=>1.01,
						"MerchantIdentifier"=>$amazon_config['MERCHANT_ID']
					),
					"MessageType"=>"ProductImage",
					$message
				)
			);

			$feed = XMLTools::Json2Xml($feed_json,true);
			file_put_contents("./temp/temp.dat", $feed);
			return AmazonAPI::submitFeed($feed,$amazon_config,"_POST_PRODUCT_IMAGE_DATA_");
		}
	}

	private static function constructUpdateProductMessage($messageIndex,$SKU, $EAN, $product, $isVariation = false, $va_name = ""){
		$title = $product->title?$product->title:"No Title";
		$brand = $product->brand?$product->brand:"Unknown Brand";
		$description = $product->description?$product->description:"No description";
		$description = "<p>".str_replace("\n", "</p><p>", $description)."</p>";
		$keywords = explode(",",$product->keywords);
		$bulletPoint = array();
		foreach($keywords as $index => $keyword){
			if($index >= 5) continue;
			$bulletPoint[] = array("BulletPoint"=>trim($keyword));
		}
		$manufacturer = $product->manufacturer?$product->manufacturer:"Unknown manufacturer";
		$productData =AmazonXSDFactory::construct($product->toArray(),$isVariation, $va_name);
		return array(
			"Message"=>array(
				"MessageID"=>$messageIndex,
				"OperationType"=>"Update",
				"Product"=>array(
					"SKU"=>$SKU,
					"StandardProductID"=>array(
						"Type"=>"EAN",
						"Value"=>$EAN
					),
					"Condition"=>array(
						"ConditionType"=>"New"
					),
					"ItemPackageQuantity"=>1,
					"NumberOfItems"=>1,
					"DescriptionData"=>array(
						"Title"=>$title,
						"Brand"=>$brand,
						"Description"=>"<![CDATA[$description]]>",
						$bulletPoint,
						"Manufacturer"=>$manufacturer,
						"RecommendedBrowseNode"=>$product->amazon_nodeId
					),
					"ProductData"=>$productData
				)
			)
		);
	}
	private static function constructDeleteProductMessage($messageIndex,$SKU){
		return array(
			"Message"=>array(
				"MessageID"=>$messageIndex,
				"OperationType"=>"Delete",
				"Product"=>array(
					"SKU"=>$SKU
				)
			)
		);
	}

	private static function constructUpdatePriceMessage($messageIndex, $SKU, $price, $currency){
		return array(
					"Message"=>array(
						"MessageID"=>$messageIndex,
						"Price"=>array(
							"SKU"=>$SKU,
							"StandardPrice"=>array(
								$price,
								"__properties"=>array(
									"currency"=>$currency
								)
							)
						)
					)
				);
	}

	private static function constructUpdateInventoryMessage($messageIndex, $SKU, $inventory){
		return array(
			"Message"=>array(
				"MessageID"=> $messageIndex,
				"OperationType"=>"Update",
				"Inventory"=>array(
					"SKU"=>$SKU,
					"Quantity"=>$inventory,
					"FulfillmentLatency"=>7
				)
			)
		);
	}

	private static function constructFeedJson($message, $amazon_config, $submitType = 'Product'){
		if(!count($message)) return false;
		if($submitType === "Relation")
			$submitType = "Relationship";
		return array(
			"AmazonEnvelope"=>array(
				"Header"=>array(
					"DocumentVersion"=>1.01,
					"MerchantIdentifier"=>$amazon_config['MERCHANT_ID']
				),
				"MessageType"=>$submitType,
				$message
			)
		);
	}

	private static function submitMessages($message, $amazon_config, $submitType = 'Product', $logOptions = null){
		$feed_json = AmazonAPI::constructFeedJson($message, $amazon_config, $submitType);
		if(!$feed_json) return 0;   //由于无修改需要，不产生任何记录

		$feed = XMLTools::Json2Xml($feed_json);

		if(null !== $logOptions && isset($logOptions["xml"])){
			file_put_contents($logOptions["xml"], $feed);
		}

		$type_map = array(
			"Product"   =>  "_POST_PRODUCT_DATA_",
			"Relation"  =>  "_POST_PRODUCT_RELATIONSHIP_DATA_",
			"Price"     =>  "_POST_PRODUCT_PRICING_DATA_",
			"Inventory" =>  "_POST_INVENTORY_AVAILABILITY_DATA_",
			"Image"     =>  "_POST_PRODUCT_IMAGE_DATA_");

		$submission_id = AmazonAPI::submitFeed($feed, $amazon_config, $type_map[$submitType]);
		if(isset($submission_id["submission_id"])){
			return $submission_id; //上传失败尽量给出提示，而不写入上传记录中
		}

		if(null !== $logOptions && isset($logOptions["uploadLog"])){
			$uploadLog = $logOptions["uploadLog"];
			$uploadLog["submission_id"] = $submission_id;
			$uploadLog["status"] = "submitted";

			$quid = Tools::getGUID();

			$log = new Uploadlog();
			$url = "./temp/updateLogs/upload_$quid.dat";
			file_put_contents($url, json_encode($uploadLog,JSON_PRETTY_PRINT));
			$log->submission_id = $submission_id;
			$log->quid = $quid;
			$log->type = $submitType;
			$log->detail_url = $url;
			$log->status = 1;
			$log->product_count = count($uploadLog["products"]);
			$log->variation_count = count($uploadLog["variations"]);
			$log->save();
		}
		return $submission_id;
	}

	public static function submitFeed($feed,$amazon_config,$submitType = "_POST_PRODUCT_DATA_"){
		$serviceUrl = $amazon_config["ServiceUrlSubmitDE"];
		$config = array (
			'ServiceURL' => $serviceUrl,
			'ProxyHost' => null,
			'ProxyPort' => -1,
			'MaxErrorRetry' => 3,
		);
		$service = new MarketplaceWebService_Client(
			$amazon_config['AWS_ACCESS_KEY_ID'], 
			$amazon_config['AWS_SECRET_ACCESS_KEY'], 
			$config,
			$amazon_config['APPLICATION_NAME'],
			$amazon_config['APPLICATION_VERSION']);

		$marketplaceIdArray = array("Id" => array('A1PA6795UKMFR9'));
		$feedHandle = @fopen('php://temp', 'rw+');
		fwrite($feedHandle, $feed);
		rewind($feedHandle);
		$parameters = array (
			'Merchant' => $amazon_config['MERCHANT_ID'],
			'MarketplaceIdList' => $marketplaceIdArray,
			'FeedType' => $submitType,
			'FeedContent' => $feedHandle,
			'PurgeAndReplace' => false,
			'ContentMd5' => base64_encode(md5(stream_get_contents($feedHandle), true)),
			'MWSAuthToken' => $amazon_config['token']
		);
		rewind($feedHandle);
		$request = new MarketplaceWebService_Model_SubmitFeedRequest($parameters);
		try{
			$response = $service->submitFeed($request);
			$result = $response->getSubmitFeedResult();
			$submissionId = $result->getFeedSubmissionInfo()->getFeedSubmissionId();
			return $submissionId;
		}catch(MarketplaceWebService_Exception $ex){
			return array(
				"submission_id"=>0,
				"Exception"=>$ex->getMessage(),
         		"status_code"=>$ex->getStatusCode(),
        		"error_code"=>$ex->getErrorCode(),
         		"error_type"=>$ex->getErrorType()
			);
		}
	}

}