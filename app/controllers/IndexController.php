<?php

class IndexController extends ControllerBase
{
	public function indexAction(){

	}
    public function indexsAction()
    {
    	$this->view->disable();
    	$seller = $this->session->get("sellerInfo");
    	$sellerId 	= $seller['sellerId'];
    	$token 		= $seller['token'];
    	include_once("../app/library/LAmazonConfig.php");
    	$serviceUrl = $amazon_config["ServiceUrl"];
    	$config = array (
		   'ServiceURL' => $serviceUrl,
		   'ProxyHost' => null,
		   'ProxyPort' => -1,
		   'ProxyUsername' => null,
		   'ProxyPassword' => null,
		   'MaxErrorRetry' => 3,
		 );

    	$service = new MarketplaceWebServiceProducts_Client(
				$amazon_config['AWS_ACCESS_KEY_ID'], 
				$amazon_config['AWS_SECRET_ACCESS_KEY'], 
				$amazon_config['APPLICATION_NAME'],
				$amazon_config['APPLICATION_VERSION'],
				$config);
    	$request = new MarketplaceWebServiceProducts_Model_GetMatchingProductRequest();
    	$request->setSellerId($sellerId);
    	$request->setMWSAuthToken($token);
    	$request->setMarketplaceId($amazon_config['MARKETPLACE_ID']);
    	$asinlist = new MarketplaceWebServiceProducts_Model_ASINListType();
    	$asinlist->setASIN(array("B07MK2YQP3"));
    	$request->setASINList($asinlist);
    	echo $token."<br/>";
    	try {
	        $response = $service->GetMatchingProduct($request);

	        echo ("Service Response\n");
	        echo ("=============================================================================\n");

	        $dom = new DOMDocument();
	        $dom->loadXML($response->toXML());
	        $dom->preserveWhiteSpace = false;
	        $dom->formatOutput = true;
	        echo $dom->saveXML();
	        echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

	     } catch (MarketplaceWebServiceProducts_Exception $ex) {
	        echo("Caught Exception: " . $ex->getMessage() . "\n");
	        echo("Response Status Code: " . $ex->getStatusCode() . "\n");
	        echo("Error Code: " . $ex->getErrorCode() . "\n");
	        echo("Error Type: " . $ex->getErrorType() . "\n");
	        echo("Request ID: " . $ex->getRequestId() . "\n");
	        echo("XML: " . $ex->getXML() . "\n");
	        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
	     }
    }

    public function welcomeAction(){
    	$this->view->setTemplateAfter("base1");
    }

    public function setSellerAction(){
    	$this->view->disable();
    	$sellerId = $this->request->getPost("sellerId");
    	$token = $this->request->getPost("token");

    	include_once("../app/library/LAmazonConfig.php");
    	$serviceUrl = $amazon_config["ServiceUrlReport"];
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

    	$parameters = array (
			'Merchant' => $sellerId,
			'AvailableFromDate' => new DateTime('-6 months', new DateTimeZone('UTC')),
			'AvailableToDate' => new DateTime('now', new DateTimeZone('UTC')),
			'MWSAuthToken' => $token,
		);

		$request = new MarketplaceWebService_Model_GetReportCountRequest($parameters);
		try{
			//通过一次报表数量的获取，验证用户编号与令牌的合法性
			$response = $service->getReportCount($request);
			$getReportCountResult = $response->getGetReportCountResult();
			$user = array(
				"sellerId" => $sellerId,
				"token" => $token
			);
			$this->session->set("sellerInfo",$user);
			$this->dataReturn(array("success"=>"/index/index"));
		}catch(MarketplaceWebService_Exception $ex){
			$error_msg = $ex->getMessage();

			if(preg_match("/\S*AuthToken is not valid for SellerId and AWSAccountId\S*/",$error_msg)){
				$error_msg = "您提供的令牌有误,请确认对本应用程序的授权";
			}

			if(preg_match("/\S*Invalid seller id\S*/", $error_msg)){
				$error_msg = "您提供的卖家编号有误，请核实";
			}
			$this->dataReturn(array("error"=>$error_msg));
		}
    }

    public function fileUploadAction(){
    	$this->view->disable();
    	if($this->request->hasFiles()){
    		$file = $this->request->getUploadedFiles()[0];
    		$fileId = $this->request->getPost("id");
    		//$uuid = substr(com_create_guid(),1,-1);
    		$filename = $fileId."_".$file->getName();
    		$file->moveTo("./img/".$filename);
    		echo $filename;
    	}

    }
}