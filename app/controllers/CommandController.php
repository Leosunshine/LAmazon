<?php
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
class CommandController extends ControllerBase
{
	public function initialize(){
		parent::initialize();
		$this->view->disable();
	}
	public function indexAction(){
		$products = Products::find("img is not null");
		echo count($products);
		foreach ($products as $index => $product) {
			if($product->img){
				$url = $product->img;
				$asin = $product->ASIN;
				$ch = curl_init($url);
				$resource = fopen("./imgCache/".$asin.".jpg","wb");
				curl_setopt($ch, CURLOPT_FILE, $resource);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				$output = curl_exec($ch);
				curl_close($ch);
			}
		}
	}

	public function loadHSCodeAction(){
		$hscode = file_get_contents("hscode.csv");
		$hscodes = explode("\n", $hscode);

		try {
			$manager = new TxManager();
			$transaction = $manager->get();
			foreach ($hscodes as $index => $hscode) {
				$elements = explode(",", $hscode);
				$hscode = new Hscodes();

				$hscode->hs_code = $elements[1];
				$hscode->name    = $elements[2];
				$unit_law = $elements[3];
				if(preg_match("/\S+{\S+}/", $unit_law)){
					$hscode->unit_law_id = explode("{",$unit_law)[0];
					$hscode->unit_law = substr(explode("{",$unit_law)[1],0,-1);			
				}
				$unit_second = $elements[4];
				if(preg_match("/\S+{\S+}/", $unit_second)){
					$hscode->unit_second_id = explode("{", $unit_second)[0];
					$hscode->unit_second = substr(explode("{",$unit_second)[1], 0,-1);					
				}


				$hscode->remark = $elements[5];
				$hscode->setTransaction($transaction);
				if($hscode->create() == false){
					$transaction->rollback("faile");
				}
			}

			$transaction->commit();
		} catch (TxFailed $e) {
			print_r($e);
		}
	}

	public function loadMatelTypesAction(){
		$content = file_get_contents("matel_types.json");
		$json = json_decode($content,true);
		echo "<pre/>";
		try {
			$manager = new TxManager();
			$transaction = $manager->get();
			foreach ($json as $index => $value) {
				foreach ($value as $key => $name) {
					$matel_type = new MatelTypes();
					$matel_type->name_en = $key;
					$matel_type->name_cn = $name;
					$matel_type->setTransaction($transaction);
					if($matel_type->create() == false){
						$transaction->rollback("failed");
						break;
					}
				}
			}
			$transaction->commit();
		} catch (TxFailed $e) {
				print_r($e);
		}

	}
	public function loadMaterialsAction(){
		$content = file_get_contents("material.json");
		$json = json_decode($content,true);
		echo "<pre/>";
		try {
			$manager = new TxManager();
			$transaction = $manager->get();
			foreach ($json as $index => $value) {
				foreach ($value as $key => $name) {
					$material = new Materials();
					$material->name_en = $key;
					$material->name_cn = $name;
					$material->setTransaction($transaction);
					if($material->create() == false){
						$transaction->rollback("failed");
						break;
					}
				}
			}
			$transaction->commit();
		} catch (TxFailed $e) {
				print_r($e);
		}
	}

	public function loadJewelAction(){
		$content = file_get_contents("jewel_type.json");
		$json = json_decode($content,true);
		echo "<pre/>";
		try {
			$manager = new TxManager();
			$transaction = $manager->get();
			foreach ($json as $index => $value) {
				foreach ($value as $key => $name) {
					$jewel_type = new JewelTypes();
					$jewel_type->name_en = $key;
					$jewel_type->name_cn = $name;
					$jewel_type->setTransaction($transaction);
					if($jewel_type->create() == false){
						$transaction->rollback("failed");
						break;
					}
				}
			}
			$transaction->commit();
		} catch (TxFailed $e) {
				print_r($e);
		}
	}

	public function loadAmazonCategoryAction(){
		$this->view->disable();
		$content = file_get_contents("./amazonClassificationCatalog.json");
		echo "<pre>";
		$content = json_decode($content,true);
		$manager = new TxManager();
		$transaction = $manager->get();
		// $root = new Amazoncategory();
		// $root->setTransaction($transaction);
		// $root->name_en = "root";
		// $root->name_cn = "根";
		// $root->parent_name = "";
		// $root->parent = 0;
		// $root->level = 0;
		// $root->is_end_point = 0;
		// $root->create();
		foreach ($content as $key => $value) {
			// $category = new Amazoncategory();
			// $category->setTransaction($transaction);
			// $category->name_en = $key;
			// $category->parent_name = "root";
			// $category->parent = 1;
			// $category->level = 1;
			// if(!isset($value['ProductType'])){
			// 	$category->is_end_point = 1;
			// }else{
			// 	$category->is_end_point = 0;
			// }

			// $category->create();
			if(isset($value['ProductType'])){
				$types = $value['ProductType'];
				$parent_id = Amazoncategory::findFirst(array(
								"name_en = :name:",
								"bind"=>array("name"=>$key)
							))->id;
				foreach ($types as $index => $type) {
					$name_en = "";
					if(is_array($type)){
						$name_en = $index;
					}else{
						$name_en = $type;
					}

					$category = new Amazoncategory();
					$category->setTransaction($transaction);
					$category->name_en = $name_en;
					$category->parent_name = $key;
					$category->parent = $parent_id;
					$category->level = 2;
					$category->is_end_point = 1;
					//$category->create();
				}
			}
		}

		//$transaction->commit();
		
		//print_r($content);
	}

	public function translateCategoryAction(){

		$manager = new TxManager();
		$transaction = $manager->get();

		$categories = Amazoncategory::find();
		$count = 0;
		foreach ($categories as $index => $category) {
			$count++;
			if($count < 5) continue;
			$category->setTransaction($transaction);
			$name_en = $category->name_en;
			$result = GoogleAPI::translate($name_en,"en","zh")['trans_result'];
			$name_cn = $result[0]["dst"];

			$category->name_cn = $name_cn;
			$category->save();
		}

		$transaction->commit();

	}
}