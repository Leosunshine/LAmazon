<?php

class AmazonResult
{
	public $submissionId = 0;
	public $status_code = "unknown";
	public $message_processed = 0;
	public $message_success = 0;
	public $message_warning = 0;
	public $message_error = 0;

	public $messages = array();
	public $success_SKUS = array();
	public $error_messages = array();
	public $warning_messages = array();

	public static function initializeFromSubmissionId($submissionId){
		//$submissionId = $submissionLog["submission_id"];
		try{
			$result = AmazonAPI::getSubmissionResult($submissionId);
		}catch(Exception $e){
			print_r($e);
			return array("error"=>$e);
		}
		
		if($result === "InputDataError") return array("error"=>"InputDataError");
		//if(preg_match("Feed Submission Result is not ready for Feed", $result)) return array("error"=>"not ready");
		
		$result = simplexml_load_string($result);
		$result = XMLTools::xmlToArray($result);
		$result = $result["AmazonEnvelope"]["Message"];
		$amazonResult = new AmazonResult();
		$amazonResult->submissionId = $result["ProcessingReport"]["DocumentTransactionID"];
		$amazonResult->status_code = $result["ProcessingReport"]["StatusCode"];

		$amazonResult->message_processed = $result["ProcessingReport"]["ProcessingSummary"]["MessagesProcessed"];
		$amazonResult->message_success = $result["ProcessingReport"]["ProcessingSummary"]["MessagesSuccessful"];
		$amazonResult->message_error = $result["ProcessingReport"]["ProcessingSummary"]["MessagesWithError"];
		$amazonResult->message_warning = $result["ProcessingReport"]["ProcessingSummary"]["MessagesWithWarning"];

		return $amazonResult;
	}

	public function isSuccess(){
		return $this->message_processed === $this->message_success;
	}
}