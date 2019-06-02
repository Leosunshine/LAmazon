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
			XMLTools::readXsd("./Arts.xsd");
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