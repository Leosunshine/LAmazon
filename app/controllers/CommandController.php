<?php
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
class CommandController extends ControllerBase
{
	public function initialize(){
		parent::initialize();
		$this->view->disable();
		set_time_limit(0);
	}
	public function indexAction(){
		$products = Products::find("img is not null");
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

	public function translateNodepathAction(){
		$nodes = AmazonNodePathsIt::find()->toArray();
		$names = array();

		foreach ($nodes as $key => $value) {
			$names[] = $value['name'];
		}
		$out = implode("\n", $names);
		file_put_contents("./word_it.txt", $out);
	}

	public function loadTranslationAction(){
		echo "<pre/>";
		$nodes = AmazonNodePathsFr::find("");
		$words = file_get_contents("./word_fr.txt");
		$trans = file_get_contents("./files/translate_fr.txt");

		$wordsLines = explode("\n", $words);
		$transLines = explode("\n",$trans);
		$coun = 0;
		foreach ($transLines as $index => $translate) {
			$word = $wordsLines[$index];
			$tran = $translate;
			if($word === $nodes[$index]->name){
				$nodes[$index]->name_remark = $tran;
				$nodes[$index]->save();
			}else{
				echo "error";
			}
		}
		// $transaction->commit();
		echo "success";
	}

	public function resetProductsAction(){
		$products = Products::find();
		foreach ($products as $index => $product) {
			$product->status = 1;
			$product->amazon_status = Tools::replaceCharAt($product->amazon_status, 0 , "1");
			$product->save();
		}

		$variations = Variation::find();
		foreach ($variations as $index => $va) {
			$va->amazon_status = Tools::replaceCharAt($va->amazon_status, 0, "1");
			$va->save();
		}
	}

	public function writeAction(){
		$nodes = AmazonNodePathsDe::find("id < 200");
		echo count($nodes);
	}

	public function writePathRemarkAction(){
		$nodes = AmazonNodePathsDe::find("level = 8");
		
		foreach ($nodes as $index => $node) {
			if($node->level === "8"){
				$parent_id = $node->parent_id;	
				$parent = AmazonNodePathsDe::findFirst($parent_id)->toArray();
				if($parent['path_remark']){
					$node->path_remark = $parent['path_remark']."/".$node->name_remark;
					$node->save();
				}
			}
		}
	}
	public function loadAmazonNodePathAction(){
		$this->view->disable();
		echo "<meta charset='utf-8'/><pre/>";
		$manager = new TxManager();
		$transaction = $manager->get();

		$content = file_get_contents("./files/csvs/watches.txt");
		$first_level_category = "Orologi";
		$contents = explode("\n", $content);
		// $root = new AmazonNodePathsIt();
		// $root->setTransaction($transaction);

		// $root->nodeId = 0;
		// $root->name = "root";
		// $root->name_remark = "根";
		// $root->level = 0;
		// $root->parent_id = 0;
		// $root->parent_name = "";
		// $root->path = "";
		// $root->is_end_point = "0";
		// $root->create();
		
		$name_id_map = array("root"=>1);
		$nodes = array();

		foreach ($contents as $key => $value) {

			$nodePathInstance = new AmazonNodePathsIt();
			$nodePathInstance->setTransaction($transaction);

			$values = explode("\t", $value);
			$nodeId = $values[0];

			if(count($values) <2){
				print_r($values);
				break;
			}
			$path = $values[1];
			if(strlen($path) >= 280) echo $path."<br/>";
			$names = explode("/", $path);
			$name = $names[count($names) - 1];
			$name = trim($name);
			$name = trim($name,"\"");
			$path = trim($path);
			$path = trim($path,"\"");
			$parent_name = count($names) > 1?substr($path, 0, -strlen($name) - 1): "root";
			$nodePathInstance->path = $path;
			$nodePathInstance->name = $name;
			$nodePathInstance->parent_name = $parent_name;
			$nodePathInstance->level = count($names);
			$nodePathInstance->nodeId = $nodeId;
			$nodePathInstance->is_end_point = 1;
			$nodePathInstance->first_level_category = $first_level_category;
			try{
			 	$nodePathInstance->save();
			 	$name_id_map[$path] = $nodePathInstance->id;
			 	$nodes[] = $nodePathInstance;
			}catch(TxFailed $e){
				print($e);
			}
		}
		

		foreach ($nodes as $key => $node) {
			if(!isset($name_id_map[$node->parent_name])){
				$path = $node->path;
				$name_array = explode("/",$path);
				$node->name = $name_array[count($name_array) - 2]."/".$name_array[count($name_array) - 1];
				$node->level = $node->level - 1;
				$node->parent_name = substr($path, 0, -strlen($node->name) - 1);
				$node->parent_id = $name_id_map[$node->parent_name];
			}else{
				$node->parent_id = $name_id_map[$node->parent_name];
			}
			
			$node->save();
		}
		try{
		 	$transaction->commit();
		}catch(TxFailed $e){
			$transaction->rollback();
			print_r($e);
		}
		echo "success";
	}

	public function writesecondAction(){
		$this->view->disable();
		$manager = new TxManager();
		$transaction = $manager->get();
		$nodes = AmazonNodePathsFr::find();
		foreach ($nodes as $key => $node) {
			if($node->level == 0) continue;
			if($node->second_level_category) continue;
			$node->setTransaction($transaction);

			if($node->level == 1){
				$node->second_level_category = $node->first_level_category."root";
			}else{
				$path = $node->path;
				$categories = explode("/",$path);
				$node->second_level_category = $categories[1];
			}
			$node->save();
		}

		try{
			$transaction->commit();
		}catch(TxFailed $e){
			$transaction->rollback();
			print_r($e);
		}
	}

	public function clear_success_uploadlogsAction(){
		$this->view->disable();
		$logs = UploadLog::find("status = 2");
		foreach ($logs as $index => $log) {
			$url = $log->detail_url;
			if(file_exists($url)){
				unlink($url);
			}
			$log->delete();
		}
	}

	/**
	输出产品数据，包括变体数据和图片数据，但是不包括图片本身,输出位于temp文件夹下，不参与同步
	*/
	public function exportProductsAction(){
		$products = Products::find();
		$filename = "./temp/product_back.json";
		$contents = array();
		foreach ($products as $id => $product) {
			$content = $product->toArray();
			$content["variations"] = array();
			$content["images"] = array();

			$variations = $product->variation_node;
			if($variations != ""){
				$variations = explode("|", $variations);
				foreach ($variations as $key => $va_id) {
					$variation = Variation::findFirst($va_id);
					$content["variations"][$va_id] = $variation->toArray();
				}
			}
			$images = $product->images;
			if($images != ""){
				$images = explode("|", $images);
				foreach ($images as $key => $img_id) {
					$image = ImageUrls::findFirst($img_id);
					$content["images"][$img_id] = $image->toArray();
				}
			}
			$contents[] = $content;
		}


		file_put_contents($filename, json_encode($contents, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	}

	/**
		导入产品数据，文件位于files文件夹下，文件参数同步
	*/
	public function importProductsAction(){
		echo "<pre/>";
		$product_content = file_get_contents("./files/product_back.json");
		$product_content = json_decode($product_content,true);

		$skip_fields = array("images","variations","id","variation_node");

		foreach ($product_content as $pid => $product) {
			$SKU = $product["SKU"];
			$product_instance = Products::findFirst(array(
				"SKU = :Product_sku:",
				"bind"=>array("Product_sku"=>$SKU)
			));

			if(!$product_instance){
				$product_instance = new Products();
			}

			foreach ($product as $field => $value) {
				if(in_array($field, $skip_fields)) continue;
				
				if(array_key_exists($field, $product_instance)){
					$product_instance->$field = $value;
				}
			}

			$product_instance->save();
			$product_id = $product_instance->id;
			echo $product_instance->id."<br/>";

			//处理图象
			$images = $product["images"];
			$images_id_map = array();
			$image_field = array();
			foreach ($images as $id => $image) {
				$guid = $image["guid"];
				$imageUrl = ImageUrls::findFirst(array(
					"guid = :guid:",
					"bind"=>array("guid"=>$guid)
				));
				if(!$imageUrl)	$imageUrl = new ImageUrls();

				$imageUrl->guid = $guid;
				$imageUrl->file_name = $image["file_name"];
				$imageUrl->url = $image["url"];
				$imageUrl->state = $image["state"];

				$imageUrl->save();
				$image_field[] = $imageUrl->id;
				$images_id_map[$image["id"]] = $imageUrl->id;
			}

			$image_field = implode("|", $image_field);

			$variations = $product["variations"];
			$variation_field = array();
			foreach ($variations as $id => $variation) {
				$va_SKU = $variation["SKU"];
				$variation_instance = Variation::findFirst(array(
					"SKU = :va_sku:",
					"bind"=>array("va_sku"=>$va_SKU)
				));

				if(!$variation_instance) $variation_instance = new Variation();

				foreach ($variation as $va_field => $value) {
					if($va_field === "id") continue;
					if($va_field === "product_id") continue;
					if($va_field === "images") continue;
					if(array_key_exists($va_field, $variation_instance)){
						$variation_instance->$va_field = $value;
					}
					$variation_instance->product_id = $product_id;
				}

				$va_images = $variation["images"];
				$va_images = explode("|", $va_images);
				foreach ($va_images as $key => $va_image) {
					$va_images[$key] = $images_id_map[$va_images];
				}
				$va_images = implode("|", $va_images);
				$variation_instance->images = $va_images;
				$variation_instance->save();
				$variation_field[] = $variation_instance->id;
			}
			$variation_field = implode("|", $variation_field);
			$product_instance->images = $image_field;
			$product_instance->variation_node = $variation_field;
			$product_instance->save();
		}
	}
}