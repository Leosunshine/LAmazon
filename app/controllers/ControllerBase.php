<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	public function initialize(){
		//$this->view->setTemplateAfter("base1");
	}

	public function dataReturn($ans){
        $this->response->setHeader("Content-Type", "text/json; charset=utf-8");
        echo json_encode($ans);
        $this->view->disable();
    }

    public function modifyUrl($url){
    	return substr($url, 1);
    }
}