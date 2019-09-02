<?php

class AmazonController extends ControllerBase
{
	public function initialize(){
		parent::initialize();
	}
	
	public function amazonuploadAction(){
		$this->view->setTemplateAfter("baseproduct");
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
		$products = Products::find()->toArray();
		$product_submission_id = AmazonAPI::createProduct($products);
		$this->dataReturn(array("success"=>$product_submission_id));
	}

	public function updateRelationshipAction(){
		$this->view->disable();
		$products = Products::find()->toArray();
		$product_submission_id = AmazonAPI::updateRelationship($products);
		$this->dataReturn(array("success"=>$product_submission_id));
	}

	public function updatePricesAction(){
		$this->view->disable();
		$products = Products::find()->toArray();
		$product_submission_id = AmazonAPI::updatePrice($products);
		$this->dataReturn(array("success"=>$product_submission_id));
	}

	public function updateInventoryAction(){
		$this->view->disable();
		$products = Products::find()->toArray();
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
}
