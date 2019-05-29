<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     MarketplaceWebService
 *  @copyright   Copyright 2009 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2009-01-01
 */
/******************************************************************************* 

 *  Marketplace Web Service PHP5 Library
 *  Generated: Thu May 07 13:07:36 PDT 2009
 * 
 */

/**
 * Get Report List  Sample
 */

include_once ('.config.inc.php'); 

/************************************************************************
* Uncomment to configure the client instance. Configuration settings
* are:
*
* - MWS endpoint URL
* - Proxy host and port.
* - MaxErrorRetry.
***********************************************************************/
// IMPORTANT: Uncomment the approiate line for the country you wish to
// sell in:
// United States:
//$serviceUrl = "https://mws.amazonservices.com";
// United Kingdom
$serviceUrl = "https://mws.amazonservices.co.uk";
// Germany
//$serviceUrl = "https://mws.amazonservices.de";
// France
//$serviceUrl = "https://mws.amazonservices.fr";
// Italy
//$serviceUrl = "https://mws.amazonservices.it";
// Japan
//$serviceUrl = "https://mws.amazonservices.jp";
// China
//$serviceUrl = "https://mws.amazonservices.com.cn";
// Canada
//$serviceUrl = "https://mws.amazonservices.ca";
// India
//$serviceUrl = "https://mws.amazonservices.in";

$config = array (
  'ServiceURL' => $serviceUrl,
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'MaxErrorRetry' => 3,
);

/************************************************************************
 * Instantiate Implementation of MarketplaceWebService
 * 
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants 
 * are defined in the .config.inc.php located in the same 
 * directory as this sample
 ***********************************************************************/
 $service = new MarketplaceWebService_Client(
     AWS_ACCESS_KEY_ID, 
     AWS_SECRET_ACCESS_KEY, 
     $config,
     APPLICATION_NAME,
     APPLICATION_VERSION);
 
/************************************************************************
 * Uncomment to try out Mock Service that simulates MarketplaceWebService
 * responses without calling MarketplaceWebService service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under MarketplaceWebService/Mock tree
 *
 ***********************************************************************/
 // $service = new MarketplaceWebService_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out 
 * sample for Get Report List Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebService_Model_GetReportListRequest
 // object or array of parameters
$parameters = array (
  'Merchant' => MERCHANT_ID,
  'AvailableToDate' => new DateTime('now', new DateTimeZone('UTC')),
  'AvailableFromDate' => new DateTime('-6 months', new DateTimeZone('UTC')),
  'Acknowledged' => false, 
  'MWSAuthToken' => 'amzn.mws.02b4f06d-85c4-26fd-f030-06456b5a1dfa', // Optional
);
$request = new MarketplaceWebService_Model_GetReportListRequest($parameters);
 
// $request = new MarketplaceWebService_Model_GetReportListRequest();
// $request->setMerchant(MERCHANT_ID);
// $request->setAvailableToDate(new DateTime('now', new DateTimeZone('UTC')));
// $request->setAvailableFromDate(new DateTime('-3 months', new DateTimeZone('UTC')));
// $request->setAcknowledged(false);
// $request->setMWSAuthToken('<MWS Auth Token>'); // Optional
 
 invokeGetReportList($service, $request);
                                                                    
/**
  * Get Report List Action Sample
  * returns a list of reports; by default the most recent ten reports,
  * regardless of their acknowledgement status
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
  */
  function invokeGetReportList(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->getReportList($request);
              
                echo ("Service Response<br/>");
                echo ("=============================================================================<br/>");

                echo("        GetReportListResponse<br/>");
                if ($response->isSetGetReportListResult()) { 
                    echo("            GetReportListResult<br/>");
                    $getReportListResult = $response->getGetReportListResult();
                    if ($getReportListResult->isSetNextToken()) 
                    {
                        echo("                NextToken<br/>");
                        echo("                    " . $getReportListResult->getNextToken() . "<br/>");
                    }
                    if ($getReportListResult->isSetHasNext()) 
                    {
                        echo("                HasNext<br/>");
                        echo("                    " . $getReportListResult->getHasNext() . "<br/>");
                    }
                    $reportInfoList = $getReportListResult->getReportInfoList();
                    foreach ($reportInfoList as $reportInfo) {
                        echo("                ReportInfo<br/>");
                        if ($reportInfo->isSetReportId()) 
                        {
                            echo("                    ReportId<br/>");
                            echo("                        " . $reportInfo->getReportId() . "<br/>");
                        }
                        if ($reportInfo->isSetReportType()) 
                        {
                            echo("                    ReportType<br/>");
                            echo("                        " . $reportInfo->getReportType() . "<br/>");
                        }
                        if ($reportInfo->isSetReportRequestId()) 
                        {
                            echo("                    ReportRequestId<br/>");
                            echo("                        " . $reportInfo->getReportRequestId() . "<br/>");
                        }
                        if ($reportInfo->isSetAvailableDate()) 
                        {
                            echo("                    AvailableDate<br/>");
                            echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "<br/>");
                        }
                        if ($reportInfo->isSetAcknowledged()) 
                        {
                            echo("                    Acknowledged<br/>");
                            echo("                        " . $reportInfo->getAcknowledged() . "<br/>");
                        }
                        if ($reportInfo->isSetAcknowledgedDate()) 
                        {
                            echo("                    AcknowledgedDate<br/>");
                            echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "<br/>");
                        }
                    }
                } 
                if ($response->isSetResponseMetadata()) { 
                    echo("            ResponseMetadata<br/>");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        echo("                RequestId<br/>");
                        echo("                    " . $responseMetadata->getRequestId() . "<br/>");
                    }
                } 

                echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "<br/>");
     } catch (MarketplaceWebService_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "<br/>");
         echo("Response Status Code: " . $ex->getStatusCode() . "<br/>");
         echo("Error Code: " . $ex->getErrorCode() . "<br/>");
         echo("Error Type: " . $ex->getErrorType() . "<br/>");
         echo("Request ID: " . $ex->getRequestId() . "<br/>");
         echo("XML: " . $ex->getXML() . "<br/>");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "<br/>");
     }
 }
?>
