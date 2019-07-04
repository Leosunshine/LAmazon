<?php
	class TestController extends ControllerBase
	{
		public function initialize(){
			parent::initialize();
		}
		public function indexAction(){
			$this->view->setTemplateAfter("base1");
		}

		public function testAction(){
			$this->view->disable();
			return;
			$query_interval = 30;
			$max_try_count = 5;
			ob_end_clean();
			header("Content-Type: text/plain");
			header("Connection: close");
			header("HTTP/1.1 200 OK");
			ob_start();
			echo "running";
			$size = ob_get_length();
			header("Content-Length:$size");
			ob_end_flush();
			flush();

			sleep(1);
			ignore_user_abort(true);
			session_write_close();
			set_time_limit(0);

			$logRecoder = new logRecoder("./temp/upload_log.txt");
			$products = Products::find()->toArray();
			$product_submission_id = AmazonAPI::createProduct($products);

			$logRecoder->add("update Product with submission id as $product_submission_id ......");
			
			$sleepCount = 0;
			$submitSuccess = false;
			$logRecoder->append("...Ready...");
			while($sleepCount < $max_try_count && !$submitSuccess){
				sleep($query_interval);
				$logRecoder->append("trying $sleepCount");
				try{
					$result =  AmazonAPI::getSubmissionResult($product_submission_id);
				}catch(Exception $e){
					$logRecoder->append($e->getMessage());
					$sleepCount++;
					continue;
				}
				
				$result_xml = simplexml_load_string($result);
				$result = XMLTools::xmlToArray($result_xml);
				if(isset($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"])){
					if($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"]*1 > 0){
						$submitSuccess = true;
						$logRecoder->append("Done successfully");
						break;
					}else{
						$logRecoder->append("Done failed"); 
						$submitSuccess = false;
						break;
					}
				}

				$sleepCount++;
			}
			return;
			if(!$submitSuccess)	return;

			$inventory_submission_id = AmazonAPI::updateInventory($products);
			$logRecoder->add("update Product's inventory with submission id as $inventory_submission_id ......");
			$sleepCount = 0;
			$submitSuccess = false;
			$logRecoder->append("...Ready...");
			while($sleepCount < $max_try_count && !$submitSuccess){
				sleep($query_interval);
				$logRecoder->append("trying $sleepCount");
				try{
					$result =  AmazonAPI::getSubmissionResult($inventory_submission_id);
				}catch(Exception $e){
					$sleepCount++;
					continue;
				}
				
				$result_xml = simplexml_load_string($result);
				$result = XMLTools::xmlToArray($result_xml);
				if(isset($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"])){
					if($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"]*1 > 0){
						$submitSuccess = true;
						$logRecoder->append("Done successfully");
						break;
					}else{
						$file_content->append("Done failed");
						$submitSuccess = false;
						break;
					}
				}
				$sleepCount++;
			}
			
			if(!$submitSuccess) return;

			$price_submission_id = AmazonAPI::updatePrice($products);
			$logRecoder->add("update Product's price with submission id as $price_submission_id ......");
			$sleepCount = 0;
			$submitSuccess = false;
			$logRecoder->append("...Ready...");
			while($sleepCount < $max_try_count && !$submitSuccess){
				sleep($query_interval);
				$logRecoder->append("trying $sleepCount");
				try{
					$result =  AmazonAPI::getSubmissionResult($price_submission_id);
				}catch(Exception $e){
					$sleepCount++;
					continue;
				}
				$result_xml = simplexml_load_string($result);
				$result = XMLTools::xmlToArray($result_xml);
				if(isset($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"])){
					if($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"]*1 > 0){
						$submitSuccess = true;
						$logRecoder->append("Done successfully");
						break;
					}else{
						$file_content->append("Done failed");
						$submitSuccess = false;
						break;
					}
				}
				$sleepCount++;
			}
			if(!$submitSuccess) return;

			$image_submission_id = AmazonAPI::uploadImage($products);
			$$logRecoder->add("upload Product's image with submission id as $image_submission_id ......");
			$sleepCount = 0;
			$submitSuccess = false;
			$logRecoder->append("...Ready...");
			while($sleepCount < $max_try_count && !$submitSuccess){
				sleep($query_interval);
				$logRecoder->append("trying $sleepCount");
				try{
					$result =  AmazonAPI::getSubmissionResult($image_submission_id);
				}catch(Exception $e){
					$sleepCount++;
					continue;
				}
				$result_xml = simplexml_load_string($result);
				$result = XMLTools::xmlToArray($result_xml);
				if(isset($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"])){
					if($result["AmazonEnvelope"]["Message"]["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"]*1 > 0){
						$submitSuccess = true;
						$logRecoder->append("Done successfully");
						break;
					}else{
						$file_content->append("Done failed");
						$submitSuccess = false;
						break;
					}
				}
				$sleepCount++;
			}
		}

		public function test2Action(){
			$this->view->disable();
			$arts = XMLTools::readXsd("./temp/xsds/EUCompliance.xsd");
			echo "<pre/>";

			$elements_origin = $arts["schema"]["xsd:element"];
			$ret = $this->handleElement($elements_origin);
			if(isset($ret[0])){
				$productType = $ret[0]["children"];
				$level_first = $ret[0]["name"];
			}else{
				$productType = $ret["children"];
				$level_first = $ret["name"];
			}
			
			$arts = array();
			$arts[$level_first] = array();
			foreach ($productType as $key => $value) {
				if("ProductType" !== $value["name"]) continue;
				$types = $value["children"];
				foreach ($types as $index => $type) {
					if(array_key_exists("ref", $type)){
						$arts[$level_first][] = $type["ref"];
					}else if(array_key_exists("name", $type)){
						$arts[$level_first][] = $type["name"];
					}						
				}

				break;
			}

			print_r($arts);
		}

		function handleElement($element){
			if(!is_array($element) && !is_object($element)) return $element;
			$ret = array();
			foreach ($element as $key => $value) {
				if(!is_array($value) && !is_object($value)){
					$ret[trim($key,"@")] = $value;
				}else{
					if("xsd:complexType" === $key){
						if(array_key_exists("xsd:sequence", $value)){
							$value_elements = $value["xsd:sequence"]["xsd:element"];
							$children = array();
							
							foreach ($value_elements as $index => $element_in) {
								if(!is_numeric($index)){
									$children[] = $this->handleElement($value_elements);
									break;
								}

							 	$children[] = $this->handleElement($element_in);
							}						
							$ret["children"] = $children;
							$ret["type"] = "sequence";
						}else if(array_key_exists("xsd:choice", $value)){
							$value_elements = $value["xsd:choice"]["xsd:element"];
							$children = array();
							foreach ($value_elements as $index => $element_in) {
								if(!is_numeric($index)){
									$children[] = $this->handleElement($value_elements);
									break;
								}

								$children[] = $this->handleElement($element_in);
							}
							$ret["children"] = $children;
							$ret["type"] = "choice";
						}
					}else if("xsd:simpleType" === $key){
						if(array_key_exists("xsd:restriction", $value)){
							$children = array();
							if(array_key_exists("xsd:enumeration", $value["xsd:restriction"])){
								$value_elements = $value["xsd:restriction"]["xsd:enumeration"];
								foreach ($value_elements as $index => $element_in) {
									if(!is_numeric($index)){
										$children[] = $value_elements["@value"];
										break;
									}
									$children[] = $element_in["@value"];
								}
								$ret["enumeration"] = $children;
								$ret["type"] = "enumeration";
							}else if (array_key_exists("xsd:maxLength", $value["xsd:restriction"])) {
								$ret["type"] = $value["xsd:restriction"]["@base"];
								$ret["maxLength"] = $value["xsd:restriction"]["xsd:maxLength"]["@value"];
							}
						}
					}else if(is_numeric($key)){
						$ret[] = $this->handleElement($value);
					}
				}
			}

			return $ret;
		}

		public function deleteProductsAction(){
			$products = Products::find()->toArray();
			echo AmazonAPI::deleteProducts($products);
		}
		public function synPriceAction(){
			$this->view->disable();
			$products = Products::find()->toArray();
			echo AmazonAPI::updatePrice($products);
		}

		public function synImagesAction(){
			$this->view->disable();
			$products = Products::find()->toArray();
			AmazonAPI::uploadImage($products);

		}
		public function getInternationalShippingComponentAction(){
			$this->view->disable();
			$file = file_get_contents("./../app/views/component/international_shipping.volt");
			echo $file;
		}

		public function getSupplierComponentAction(){
			$this->view->disable();
			$file = file_get_contents("./../app/views/component/supplier.volt");
			echo $file;
		}

		public function testTransAction(){
			$this->view->disable();
			$word = "Bekleidung";
			$result = GoogleAPI::translate($word,"de","zh")["trans_result"];
			print_r($result);
		}
		function getGUID(){
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

		
	}