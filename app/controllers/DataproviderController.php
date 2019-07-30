<?php

class DataproviderController extends ControllerBase
{
	public function initialize(){
		parent::initialize();
	}
	public function getHscodeAction(){
		$this->view->disable();
		$condition = $this->request->getPost("condition","string");
		$condition = trim($condition);
		$result = $this->modelsManager->createBuilder()
				->columns(array(
					'Hscodes.hs_code as hs_code',
					'Hscodes.name as name'
				))
				->from('Hscodes')
				->where("hs_code like '%$condition%' or name like '%$condition%'")
				->limit(100,0)
				->orderBy('hs_code')
				->getQuery()
				->execute()
				->toArray();
		$this->dataReturn(array("success"=>$result));
	}

	public function listProductAction(){
		$this->view->disable();
		$limit = $this->request->getPost("limit") + 0;
		$offset = $this->request->getPost("offset") + 0;
		$orderBy = $this->request->getPost("orderBy");
		$asc = $this->request->getPost("asc");

		$result = $this->modelsManager->createBuilder()
				->columns(array(
					'Products.id as id',
					'Products.title as title',
					'Products.fixed_shipping as fixed_shipping',
					'Products.price as price',
					'Products.currency as currency',
					'Products.images as images'
				))
				->from("Products")
				->limit($limit,$offset)
				->orderBy($orderBy." ".$asc)
				->getQuery()
				->execute()
				->toArray();
		foreach ($result as $index => $value) {
			$images = $result[$index]['images'];
			$images = explode("|", $images);
			if(count($images) > 0 && $images[0]){
				$image = ImageUrls::findFirst($images[0] * 1)->toArray();
				$cover = $this->modifyUrl($image["url"]);
				$result[$index]["cover"] = $cover;
			}

			switch ($value['currency']) {
				case "人民币": $result[$index]["currency"] = "￥";break;
				case "美元": $result[$index]["currency"] = "$"; break;
				case "欧元": $result[$index]["currency"] = "€"; break;
				case "英磅": $result[$index]["currency"] = "£"; break;
				case "加元": $result[$index]["currency"] = "C$"; break;
				case "日元": $result[$index]["currency"] = "J￥"; break;
				case "澳元": $result[$index]["currency"] = "A$"; break;
				case "墨西哥元": $result[$index]["currency"] = "Mex$"; break;
				case "印度卢比": $result[$index]["currency"] = "₹"; break;
				case "雷加尔": $result[$index]["currency"] = "R$"; break;
				default:
					break;
			}
		}
		$this->dataReturn(array("success"=>$result));
	}

	public function getProductByIdAction(){
		$this->view->disable();
		$product_id = $this->request->getPost("id");
		$product_instance = Products::findFirst($product_id)->toArray();
		$images = $product_instance['images'];
		$images_return = array();
		if($images){
			$images = explode("|", $images);
			foreach($images as $index => $image_id){
				//将图片取出，但是不做改动，将来参product做update时，再将未使用的图片置0
				$image_instance = ImageUrls::findFirst($image_id)->toArray();
				if(!$image_instance) continue;
				$image_instance['url'] = $this->modifyUrl($image_instance["url"]);
				//$images_return[$image_instance['id']] = $image_instance;
				$images_return[$index] = $image_instance;
			}
		}

		$variations = $product_instance['variation_node'];
		$variations_return = array();
		if($variations){
			$variations = explode("|",$variations);
			foreach ($variations as $index => $variation_id) {
				$variation_instance = Variation::findFirst($variation_id)->toArray();
				if(!$variation_instance) continue;
				$variations_instance_images = $variation_instance['images'];
				if($variations_instance_images){
					$variations_instance_images = explode("|",$variations_instance_images);
				}else{
					$variations_instance_images = array();
				}
				$variation_instance['images'] = $variations_instance_images;
				$variations_return[] = $variation_instance;
			}
		}

		$themes = Amazoncategory::findFirst($product_instance['amazon_category_id'])->toArray();
		$themes = $themes["variation_theme"];
		$product_instance['images'] = $images_return;
		$product_instance['variation_node'] = $variations_return;
		$product_instance['themes'] = $themes;

		$this->dataReturn(array("success"=>$product_instance));
	}

	public function listCategoryAction(){
		$this->view->disable();
		$categories = Amazoncategory::find()->toArray();
		$ret = array();
		$dictionary = array();
		foreach ($categories as $key => $category) {
			if($category['level'] == 1){
				if($category['is_end_point'] == 1){
					$ret[$category['name_en']] = $category['id'];
				}else{
					$ret[$category['name_en']] = array();
				}
			}
		}

		foreach ($categories as $key => $category) {
			if($category['level'] == 2){
				$parent_name = $category['parent_name'];
				if(isset($ret[$parent_name])){
					if($category['is_end_point'] == 1){
						$ret[$parent_name][$category['name_en']] = $category['id'];
					}else{
						$ret[$parent_name][$category['name_en']] = array();
					}
				}
			}
		}

		foreach ($categories as $key => $category) {
			$dictionary[$category['name_en']] = $category['name_cn'];
		}
		$this->dataReturn(array("success"=>$ret,'dictionary'=>$dictionary));
	}
	public function getsuppliersAction(){
		$this->view->disable();
		$page = $this->request->get('page');
		$rows = $this->request->get('rows');
		$offset = $rows*($page-1);
		$limit = $rows;
		$sidx = $this->request->getQuery('sidx','string');
		$sord = $this->request->getQuery('sord','string');
		if(!$sidx) $sidx = "id";
		if($sord){
			$sord = "$sidx $sord";
		}else{
			$sord = "$sidx asc";
		}

		$count = Suppliers::count();
		$result = $this->modelsManager->createBuilder()
				->columns(array(
					'Suppliers.id as id',
					'Suppliers.name as name',
					'Suppliers.QQ as QQ',
					'Suppliers.tel as tel',
					'Suppliers.remark as remark'
				))
				->from("Suppliers")
				->limit($limit,$offset)
				->orderBy($sord)
				->getQuery()
				->execute()
				->toArray();
		$ret = array();
		$ret['rows'] = $result;
		$ret['total'] = ceil($count/$rows);
		$ret['records'] = $count;
		$this->dataReturn($ret);
	}

	public function getInternationalShippingsAction(){
		$this->view->disable();
		$page = $this->request->get('page');
		$rows = $this->request->get('rows');
		$offset = $rows*($page-1);
		$limit = $rows;
		$sidx = $this->request->getQuery('sidx','string');
		$sord = $this->request->getQuery('sord','string');
		if(!$sidx) $sidx = "id";
		if($sord){
			$sord = "$sidx $sord";
		}else{
			$sord = "$sidx asc";
		}

		$count = InternationalShipping::count();
		$result = $this->modelsManager->createBuilder()
				->columns(array(
					'InternationalShipping.id as id',
					'InternationalShipping.name as name',
					'InternationalShipping.country as country',
					'InternationalShipping.method as method',
					'InternationalShipping.starting_price as starting_price',
					'InternationalShipping.starting_weight as starting_weight',
					'InternationalShipping.unit_price as unit_price',
					'InternationalShipping.per_weight as per_weight',
					'InternationalShipping.registration_fee as registration_fee',
					'InternationalShipping.discount as discount',
					'InternationalShipping.currency as currency',
					'InternationalShipping.weight_unit as weight_unit',
					'InternationalShipping.weight_factor as weight_factor'
				))
				->from("InternationalShipping")
				->limit($limit,$offset)
				->orderBy($sord)
				->getQuery()
				->execute()
				->toArray();
		$ret = array();
		$ret['rows'] = $result;
		$ret['total'] = ceil($count/$rows);
		$ret['records'] = $count;
		$this->dataReturn($ret);
	}

	public function listLocalCategoryAction(){
		$result = Localcategory::find('level < 3')->toArray();
		$this->dataReturn($result);
	}

	public function listAmazonNodesAction(){
		$this->view->disable();
		$max_level = $this->request->getPost("max_level");
		$first_level_category = $this->request->getPost('first');
		$second_level_category = $this->request->getPost('second');

		$binds = array();
		if("__any" === $first_level_category){
			$first_condition = "";	
		}else{
			//$first_condition = " and first_level_category = :first:";
			$first_condition = " and first_level_category = '$first_level_category'";
			$binds['first'] = $first_level_category;
		}

		if("__any" === $second_level_category){
			$second_condition = "";
		}else{
			//$second_condition = " and (second_level_category = :second: or second_level_category = :second_root:)";
			$second_condition = " and (second_level_category = '$second_level_category' or second_level_category = '$first_level_category"."root')";
			$binds['second'] = $second_level_category;
			$binds['second_root'] = $first_level_category."root";
		}

		if($max_level){
			//$max_condition = "level <= :max_level:";
			$max_condition = "level <= $max_level";
			$binds['max_level'] = $max_level;
		}else{
			$max_condition = "1 = 1";
		}
		$condition = "$max_condition $first_condition $second_condition";

		$nodes = $this->modelsManager->createBuilder()
				->columns(
					array(
						'AmazonNodePathsDe.id as id',
						'AmazonNodePathsDe.nodeId as nodeId',
						'AmazonNodePathsDe.name_remark as name',
						'AmazonNodePathsDe.name as name_de',
						'AmazonNodePathsDe.parent_name as parent_name',
						'AmazonNodePathsDe.path_remark as path',
						'AmazonNodePathsDe.path as path_de',
						'AmazonNodePathsDe.parent_id as parent_id',
						'AmazonNodePathsDe.first_level_category as first_level_category',
						'AmazonNodePathsDe.second_level_category as second_level_category'
					)
				)
				->from("AmazonNodePathsDe")
				->where($condition)
				->getQuery()
				->execute()
				->toArray();

		$this->dataReturn($nodes);

	}
}