<?php

class AmazonController extends ControllerBase
{
	public function initialize(){
		parent::initialize();
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
		$products = products::find()->toArray();
		$product_submission_id = (new AmazonAPI())->createProduct($products);
		echo $product_submission_id;
	}
}
