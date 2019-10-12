<?php

class AmazonController extends ControllerBase
{
	public function initialize(){
		parent::initialize();
	}
	
	public function amazonuploadAction(){
		$this->view->setTemplateAfter("baseproduct");
		$seller = $this->session->get("userInfo");
		$this->view->setVar("username",$seller['username']);
	}
	public function deleteAction(){
		$this->view->setTemplateAfter("base1");
	}

	public function deleteInterfaceAction(){
		$this->view->disable();
		$SKU = $this->request->getPost("SKU");
		echo AmazonAPI::deleteAProduct($SKU);
	}

	public function queryAction(){
		$this->view->setTemplateAfter("base1");
	}
	public function queryInterfaceAction(){
		$this->view->disable();
		$submissionId = $this->request->getPost("submissionId");
		try{
			echo AmazonAPI::getSubmissionResult($submissionId);
		}catch(Exception $e){
			print_r($e->getErrorMessage());
		}
		
	}

	public function updateProductsAction(){
		$this->view->disable();
		$products = Products::find();
		$product_submission_id = AmazonAPI::updateProduct($products);
		if(!isset($product_submission_id["submission_id"])){
			$this->dataReturn(array("success"=>$product_submission_id));
		}else{
			$this->dataReturn(array("error"=>$product_submission_id));
		}
		
	}

	public function updateRelationshipAction(){
		$this->view->disable();
		$products = Products::find();
		$product_submission_id = AmazonAPI::updateRelationship($products);
		$this->dataReturn(array("success"=>$product_submission_id));
	}

	public function updatePricesAction(){
		$this->view->disable();
		$products = Products::find("status < 6");
		$product_submission_id = AmazonAPI::updatePrice($products);
		$this->dataReturn(array("success"=>$product_submission_id));
	}

	public function updateInventoryAction(){
		$this->view->disable();
		$products = Products::find();
		$submissionId = AmazonAPI::updateInventory($products);
		$this->dataReturn(array("success"=>$submissionId));
	}

	public function updateShippingAction(){
		$this->view->disable();
		$products = Products::find()->toArray();
		$submissionId = AmazonAPI::updateShipping($products);
		$this->dataReturn(array("success"=>$submissionId));
	}

	public function updateImagesAction(){
		$this->view->disable();
		$products = Products::find()->toArray();
		$submissionId = AmazonAPI::uploadImage($products);
		$this->dataReturn(array("success"=>$submissionId));
	}

	public function getpreparedcountAction(){
		$this->view->disable();
		$products = Products::find("status < 7");
		$product_count = Tools::countOfPreparedProduct($products);
		$counts = Tools::countOfPreparedOther($products);

		$this->dataReturn(array("success"=>array("products"=>$product_count, "relation" => $counts["relation"], "price"=>$counts["price"], "inventory"=>$counts["inventory"])));
	}

	public static function getResult(){
		$submission = file_get_contents("./temp/updateLogs/uploadLog.dat");
		$submission = json_decode($submission,true);
		$submissionId = $submission["submission_id"];
		$result = AmazonAPI::getSubmissionResult($submissionId);
		if($result === "InputDataError") return array("error"=>"InputDataError");

		$result = simplexml_load_string($result);
		$result = XMLTools::xmlToArray($result);
		print_r($result);
		$messages = $result["AmazonEnvelope"]["Message"];
		$report = $messages["ProcessingReport"];
		$status = $report["StatusCode"];
		if($status === "Complete"){
			$total = $report["ProcessingSummary"]["MessagesProcessed"];
			$success = $report["ProcessingSummary"]["MessagesSuccessful"];
			//仅处理没有报错的情况,报错请手动处理错误
			if($success === $total){
				$products = $submission["products"];
				foreach ($products as $product_SKU => $product) {
					$product_instance = Products::find(array(
						"SKU = :SKU:",
						"bind"=>array("SKU"=>$product_SKU)
					));
					if(!$product_instance) continue;

					$product_instance = $product_instance[0];
					if($product_instance->status == 1){
						$product_instance->status = 2;
						$product_instance->amazon_status = Tools::replaceCharAt($product_instance->amazon_status, 0, "2");
					}else{
						$product_instance->status = 5;
						$product_instance->amazon_status = Tools::replaceCharAt($product_instance->amazon_status, 0, "5");
					}
					
					$vas = $product;
					foreach ($vas as $index => $va_SKU) {
						$variation = variation::find(array(
							"SKU = :SKU:",
							"bind"=>array("SKU"=>$va_SKU)
						));

						if(!$variation) continue;
						$variation = $variation[0];
						$variation_status = substr($variation->amazon_status, 0, 1);
						if($variation_status === "1"){
							$variation->amazon_status = Tools::replaceCharAt($variation->amazon_status, 0, "2");
						}else{
							$variation->amazon_status = Tools::replaceCharAt($variation->amazon_status, 0, "5");
						}
						$variation->save();
					}
					$product_instance->save();
				}


			}else{
				echo "error";
			}
		}
	}
}
