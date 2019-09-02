<?php
class AmazonAPI
{
	public static function createProduct($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;
		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			$EAN = $product['ASIN'];
			$perpackage_count = $product['perpackage_count']?($product['perpackage_count'] * 1):1;
			$title = $product['title']?$product['title']:"No Title";
			$brand = $product['brand']?$product['brand']:"Unknown Brand";
			$description = $product['description']?$product['description']:"No description";
			$description = "<p>".str_replace("\n", "</p><p>", $description)."</p>";

			$keywords = explode(",",$product['keywords']);
			$bulletPoint = array();
			foreach($keywords as $index => $keyword){
				if($index >= 5) continue;
				$bulletPoint[] = array("BulletPoint"=>trim($keyword));
			}
			$manufacturer = $product['manufacturer']?$product['manufacturer']:"Unknown manufacturer";
			$category = $product['amazon_category_id'];
			$category = Amazoncategory::findFirst($category)->toArray();
			$productData =AmazonXSDFactory::construct($product);

			$message[] = array(
				"Message"=>array(
					"MessageID"=>$messageIndex++,
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
						"NumberOfItems"=>$perpackage_count,
						"DescriptionData"=>array(
							"Title"=>$title,
							"Brand"=>$brand,
							"Description"=>"<![CDATA[$description]]>",
							$bulletPoint,
							"Manufacturer"=>$manufacturer,
							"RecommendedBrowseNode"=>$product['amazon_nodeId']
						),
						"ProductData"=>$productData
					)
				)
			);

			$variations = $product['variation_node'];
			if(!$variations) continue;
			$variations = explode("|", $variations);
			$variation_theme = $product['variation_theme'];

			foreach ($variations as $index => $value) {
				$variation_instance = Variation::findFirst($value)->toArray();
				if(!$variation_instance) continue;
				$va_SKU = $variation_instance["SKU"];
				$va_name = $variation_instance["name"];
				$va_ASIN = $variation_instance["EAN"];

				$message[] = array(
					"Message"=>array(
						"MessageID"=>$messageIndex++,
						"OperationType"=>"Update",
						"Product"=>array(
							"SKU"=>$va_SKU,
							"StandardProductID"=>array(
								"Type"=>"EAN",
								"Value"=>$va_ASIN
							),
							"DescriptionData"=>array(
								"Title"=>$title." ".$va_name,
								"Brand"=>$brand,
								"Description"=>"<![CDATA[$description]]>",
								$bulletPoint,
								"Manufacturer"=>$manufacturer,
								"RecommendedBrowseNode"=>$product['amazon_nodeId']
							),
							"ProductData"=>array(
								"Home"=>array(
									"ProductType"=>array(
										"Home"=>array(
											"Material"=>$product['material_type']?$product['material_type']:"unknown"
										)
									),
									"Parentage"=>"child",
									"VariationData"=>array(
										$variation_theme=>$va_name
									)
								)
							)
						)
					)
				);	
			}
		}

		$feed_json = array(
			"AmazonEnvelope"=>array(
				"Header"=>array(
					"DocumentVersion"=>1.01,
					"MerchantIdentifier"=>$amazon_config['MERCHANT_ID']
				),
				"MessageType"=>"Product",
				"PurgeAndReplace"=>"false",
				$message
			)
		);
		$feed = XMLTools::Json2Xml($feed_json);
		file_put_contents("./temp/temp.dat", $feed);
		return AmazonAPI::submitFeed($feed,$amazon_config);
	}

	public static function updatePrice($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;

		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			$currency = $product['currency'];
			$price = $product['price'];
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

			$variations = $product["variation_node"];
			if($variations){
				$variations = explode("|", $variations);
				foreach ($variations as $key => $value) {
					$variation_instance = Variation::findFirst($value)->toArray();
					$va_SKU = $variation_instance["SKU"];
					$va_price_bonus = $variation_instance["price_bonus"];
					$va_price = $price * 1.0 + $va_price_bonus * 1.0;
					$message[] = array(
						"Message"=>array(
							"MessageID"=>$messageIndex++,
							"Price"=>array(
								"SKU"=>$va_SKU,
								"StandardPrice"=>array(
									$va_price,
									"__properties"=>array(
										"currency"=>"EUR"
									)
								)
							)
						)
					);
				}
			}else{
				$message[] = array(
					"Message"=>array(
						"MessageID"=>$messageIndex++,
						"Price"=>array(
							"SKU"=>$SKU,
							"StandardPrice"=>array(
								$price,
								"__properties"=>array(
									"currency"=>"EUR"
								)
							)
						)
					)
				);
			}
			
		}
		$feed_json = array(
			"AmazonEnvelope"=>array(
				"Header"=>array(
					"DocumentVersion"=>1.01,
					"MerchantIdentifier"=>$amazon_config['MERCHANT_ID']
				),
				"MessageType"=>"Price",
				$message
			)
		);

		$feed = XMLTools::Json2Xml($feed_json);
		return AmazonAPI::submitFeed($feed,$amazon_config,"_POST_PRODUCT_PRICING_DATA_");
	}
	
	public static function updateRelationship($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;
		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			$variations = $product["variation_node"];
			$variations = explode("|", $variations);
			$relation = array();
			foreach ($variations as $key => $variation) {
				$variation_instance = Variation::findFirst($variation)->toArray();

				$relation[] = array(
					"Relation"=>array(
						"SKU"=>$variation_instance["SKU"],
						"Type"=>"Variation"
					)
				);
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
		}

		$feed_json = array(
			"AmazonEnvelope"=>array(
				"Header"=>array(
					"DocumentVersion"=>1.01,
					"MerchantIdentifier"=>$amazon_config['MERCHANT_ID']
				),
				"MessageType"=>"Relationship",
				"PurgeAndReplace"=>"false",
				$message
			)
		);

		$feed = XMLTools::Json2Xml($feed_json);
		file_put_contents("./temp/temp.dat", $feed);
		return AmazonAPI::submitFeed($feed,$amazon_config,"_POST_PRODUCT_RELATIONSHIP_DATA_");

	}
	public static function updateInventory($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;
		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			$variations = $product["variation_node"];
			if($variations){
				$variations = explode("|", $variations);
				foreach ($variations as $key => $value) {
					$variation_instance = Variation::findFirst($value)->toArray();
					$va_SKU = $variation_instance["SKU"];
					$va_count = $variation_instance["inventory_count"];
					$message[] =array(
						"Message"=>array(
							"MessageID"=> $messageIndex++,
							"OperationType"=>"Update",
							"Inventory"=>array(
								"SKU"=>$va_SKU,
								"Quantity"=>$va_count,
								"FulfillmentLatency"=>7
							)
						)
					);
				}
			}else{
				$quantity = $product['product_count'];
				$message[] = 
					array(
						"Message"=>array(
							"MessageID"=> $messageIndex++,
							"OperationType"=>"Update",
							"Inventory"=>array(
								"SKU"=>$SKU,
								"Quantity"=>$quantity,
								"FulfillmentLatency"=>7
							)
						)
					);
			}

		}

		$feed_json = array(
			"AmazonEnvelope"=>array(
				"Header"=>array(
					"DocumentVersion"=>1.01,
					"MerchantIdentifier"=>$amazon_config['MERCHANT_ID']					
				),
				"MessageType"=>"Inventory",
				"PurgeAndReplace"=>"false",
				$message
			)
		);

		$feed = XMLTools::Json2Xml($feed_json);
		file_put_contents("./temp/temp.dat", $feed);
		return AmazonAPI::submitFeed($feed,$amazon_config,"_POST_INVENTORY_AVAILABILITY_DATA_");
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
		$response = $service->submitFeed($request);
		$result = $response->getSubmitFeedResult();
		$submissionId = $result->getFeedSubmissionInfo()->getFeedSubmissionId();
		return $submissionId;
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
}