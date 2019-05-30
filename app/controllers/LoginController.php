<?php

class LoginController extends ControllerBase
{
	public function initialize(){

	}

    public function indexAction()
    {

    }

    public function verifyUserAction(){
    	$this->view->disable();
    	$username = $this->request->getPost("username");
    	$password = $this->request->getPost("password");

    	$user = Users::findFirst(array(
    		'username = :user: and password = :pwd:',
    		'bind'=>array('user'=>$username,'pwd'=>$password)
    	));

    	if(!$user) $this->dataReturn(array("error"=>"error"));

    	$this->session->set("userInfo",$user->toArray());
    	$this->dataReturn(array("success"=>"success"));
    }

    public function inituserAction(){
    	$this->view->disable();
    	$user = Users::findFirst();
    	$user->password = "7c4a8d09ca3762af61e59520943dc26494f8941b";
    	$user->save();
    }
}
