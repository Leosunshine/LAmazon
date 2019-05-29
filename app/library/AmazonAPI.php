<?php
class AmazonAPI
{
	public static function createProduct($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			$EAN = $product['ASIN'];
			$perpackage_count = $product['perpackage_count']?($product['perpackage_count'] * 1):1;
			$title = $product['title']?$product['title']:"No Title";
			$brand = $product['brand']?$product['brand']:"Unknown Brand";
			$description = $product['description']?$product['description']:"No description";
			$keywords = explode(",",$product['keywords']);
			$bulletPoint = array();
			foreach($keywords as $index => $keyword){
				$bulletPoint[] = array("BulletPoint"=>trim($keyword));
			}
			$manufacturer = $product['manufacturer']?$product['manufacturer']:"Unknown manufacturer";

			$category = $product['amazon_category_id'];
			$category = Amazoncategory::findFirst($category)->toArray();
			$productData = array(
				"Home"=>array(
					"ProductType"=>array(
						"Chair"=>array(
							"IdentityPackageType"=>"bulk"
						)
					)
				)
			);

			//默认使用Home/Chair分类
			// if($category['level'] == 1){
			// 	continue;    //Clothing ClothingAccessory 还有 Tools 三种情况还未做处理
			// }else if($category['level'] == 2){
			// 	$productData[$category['parent_name']] = array(
			// 		"ProductType"=>array(
			// 			$category['name_en']=>""
			// 		)
			// 	);
			// }else{
			// 	continue;
			// }


			$message[] = array(
				"MessageID"=>($index + 1),
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
						"Description"=>$description,
						$bulletPoint,
						"Manufacturer"=>$manufacturer
					),
					"ProductData"=>$productData
				)
			);
		}

		$feed_json = array(
			"AmazonEnvelope"=>array(
				"Header"=>array(
					"DocumentVersion"=>1.01,
					"MerchantIdentifier"=>$amazon_config['MERCHANT_ID']
				),
				"MessageType"=>"Product",
				"PurgeAndReplace"=>"false",
				"Message"=>$message
			)
		);

		$feed = XMLTools::Json2Xml($feed_json);
		return AmazonAPI::submitFeed($feed,$amazon_config);
	}

	public function updatePrice($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();

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
			$message[] = array(
				"Message"=>array(
					"MessageID"=>($index + 1),
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
	
	public function updateInventory($products){
		$amazon_config = LAmazonConfig::$amazon_config;
		$message = array();
		$messageIndex = 1;
		foreach ($products as $index => $product) {
			$SKU = $product['SKU'];
			$quantity = $product['product_count'];
			$message[] = 
				array(
					"Message"=>array(
						"MessageID"=>($index + 1),
						"OperationType"=>"Update",
						"Inventory"=>array(
							"SKU"=>$SKU,
							"Quantity"=>$quantity,
							"FulfillmentLatency"=>7
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
				"MessageType"=>"Inventory",
				"PurgeAndReplace"=>"false",
				$message
			)
		);

		$feed = XMLTools::Json2Xml($feed_json);
		return AmazonAPI::submitFeed($feed,$amazon_config,"_POST_INVENTORY_AVAILABILITY_DATA_");
	}
	public function deleteAProduct($SKU){
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
		
		$SKU = Tools::get_GUID();
		$SKU = substr($SKU, 28, 8);
		$SKU = "LAMAZON-".$SKU;
		return $SKU;
	}

	public static function composeEAN(){
		$ean13 = "6093128";				//借用毛里求斯的EAN
		$ean13 = str_pad($ean13,6,"0",STR_PAD_LEFT);
		$ean132 = rand(1,99999);
		$ean132 = str_pad($ean132,6,"0", STR_PAD_LEFT);
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
			$main_image_url = "http://152.136.12.173/a.jpg";
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

			$message[] = array(
				"Message"=>array(
					"MessageID"=>$messageIndex++,
					"OperationType"=>"Update",
					"ProductImage"=>array(
						"SKU"=>$SKU,
						"ImageType"=>"PT3",
						"ImageLocation"=>$main_image_url
					)
				)
			);

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

			$feed = XMLTools::Json2Xml($feed_json);
			return AmazonAPI::submitFeed($feed,$amazon_config,"_POST_PRODUCT_IMAGE_DATA_");
		}
		

	}
}