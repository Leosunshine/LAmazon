<?php

class UserController extends ControllerBase
{
	public function initialize(){
		parent::initialize(); //验证登录行为
	}

	public function homepageAction(){

	}

	public function updateshopAction(){
		$this->view->disable();
		$data = json_decode($this->request->getPost("data"),true);

		$shop = Shop::findFirst($data["id"]);
		if(!$shop){
			$this->dataReturn(array("error"=>"shop nout found"));return;
		}

		$shop->name = $data["name"];
		$shop->amazon_merchant_id = $data["amazon_merchant_id"];
		$shop->amazon_token = $data["amazon_token"];
		$shop->save();

		$this->dataReturn(array("success"=>"success"));
	}

	public function updatepwdAction(){
		$this->view->disable();
		$seller = $this->session->get("userInfo");
		$sellerId = $seller["id"];
		$seller = Users::findFirst($sellerId);

		$oldpwd = $this->request->getPost("oldpwd");
		$newpwd = $this->request->getPost("newpwd");

		if($seller->password != $oldpwd){
			$this->dataReturn(array("error"=>"原密码输入错误"));
			return;
		}

		$seller->password = $newpwd;
		$seller->save();
		$this->dataReturn(array("success"=>"success"));
	}
}