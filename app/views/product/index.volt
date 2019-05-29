<!--<!Doctype html>-->
<style type="text/css">
	
	select{
		display:inline-block;
		width:auto;
	}

	input{
		display:inline-block;
		width:auto;
	}

	textarea{
		width:90%;
	}
	.productCardOutter{
		width:200px;
		height:250px;
		float:left;
		background-color:white;
	}

	.productCardInner{
		width:180px;
		height:230px;
		margin:0 auto;
		margin-top:10px;
	}

	.productCardInner_hover{
		width:180px;
		height:230px;
		margin:0 auto;
		margin-top:9px;
	}

	.imgContainer{
		width: 180px;
		height:180px;
		margin:0 auto;
		margin-top:10px;
	}

	.imgContainer img{
		max-width:100%;
		max-height:100%;
		margin:0;
		padding:0;
	}

	.imgContainer_cover{
		position:relative;left:0;top:-100%;width:100%;height:100%;background-color:rgba(0,0,0,0.001);
	}

	#search_condition{
		width:100%;
		height:10%;margin: 0 auto;
	}
	#productContainer{
		width:97%;
		height:87%;
		overflow-y: auto;
		margin: 0 auto;
	}

	.price_panel{
		width:90%;
		height:15px;
		margin:0 auto;
		text-align:left;
	}

	.title_panel{
		padding: 5px;
		width:180px;
		height:26px;
		line-height:15px;
		font-size:12px;
		cursor: pointer;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient:vertical;
		overflow: hidden;
	}
</style>
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<script type="text/javascript" src="/js/uuid.js"></script>
<link rel="stylesheet" type="text/css" href="/jqGrid/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/font-awesome/css/font-awesome.css">

<script type="text/javascript" src="/js/jquery-imageuploader.js"></script>
<script type="text/javascript" src="/js/product-manager.js"></script>

<script type="text/javascript" src="/jqGrid/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="/jqGrid/js/i18n/grid.locale-cn.js"></script>
<script type="text/javascript" src="/jqGrid/js/jqgrid.assist.js"></script>
<script type="text/javascript">
	$(function(){
		$("textarea").each(function(){
			if($(this).attr("autoheight")){
				$(this).keyup(function(){
					var v = $(this).val();
					var cols = $(this).prop("cols");
					var rows = 1;
					var lines = v.split("\n"); //用换行符切割
					for(var i = 0; i < lines.length; i++){
						var r = Math.ceil(lines[i].length / cols);
						r = (r == 0)?1:r;
						rows += r;
					}
					$(this).prop("rows",rows<2?2:rows);
				});
			}
		});
	});
</script>
<div id="navigation" style="width:100%;height:10%;background-color:yellow;">
	<div style="width:10%;height:100%;float:left;"></div>
	<div style="width:10%;height:100%;font-size: 25px;float:left;cursor:pointer;" onclick="window.location.href='#'"><div style="height:20%;"></div>产品管理<span class="fa fa-pencil"></span></div>
</div>
<div id="downcontent" style="width:100%;height:90%;">
	<div id="entry" style="width:18%;height:100%;float:left;">
		<div style="width:100%;height:5%;"></div>
		<div id="group_panel" style="width:90%;height:50%;margin:auto;background-color: rgba(202,133,106,1);">分类树形图区</div>
		<hr>
		<div id="supplier_entry" style="width:90%;height:10%;background-color: silver; margin:auto; text-align: center;">供应商</div>
		<hr>
		<div id="international_shipping_entry" style="width:90%;height:10%;background-color: silver; margin:auto; text-align: center;">国家运费</div>

		<script type="text/javascript">
			$(function(){
				$("#supplier_entry").click(function(){
					$.post("/test/getSupplierComponent",undefined,function(data){
						$("#content").html(data);
					});
				});

				$("#international_shipping_entry").click(function(){
					$.post("/test/getInternationalShippingComponent",undefined,function(data){
						$("#content").html(data);
					})
				});
			});
		</script>
	</div>
	<div id="content" style="width:82%;height:100%;float:left;">
		<div id="search_condition">
			<div style="width:70%;height:100%;float:left;background-color:red;">
				<button class="btn-primary" onclick="productManager.refreshProductList(10,0,undefined,undefined,undefined);">加载商品</button>
			</div>
			<div style="width:30%;height:100%;float:left;background-color:rgba(202,133,106,1);">
				<button class="btn-primary" onclick = "showProductForm(undefined,true);">添加商品</button>
				<button class="btn-danger" onclick="truncateDatabase();">清空数据库</button>
			</div>
		</div>
		<div id="productContainer"></div>
		<div id="content_footer" style="background-color:cyan;width:100%;height:3%;"></div>
	</div>
</div>


<div id="addProductPanel" isNew=true style="position:absolute;left:0;top:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%;display:none;">
	<div class="well" style="width:80%;height:80%;background-color:white;margin:0 auto;margin-top:5%;">
		<div style="width:100%;height:5%;font-size: 20px;">新增商品</div>
		<hr/>
		<div style="width:100%;height:80%;overflow-y: auto;">
			<div style="width:100%;height:80%;">
				<div style="width:50%;height:100%;line-height: 20px;float:left;">
					选择分类:<br/>
					<select id="category_options_0">
						
					</select>
					<select id="category_options_1">
						
					</select>
					<br/>
					<span style="font-weight: bold;">审核状态:</span>	
								<input class="product_field product_panel_radio_selector" type="radio" name="review_status" value="1">通过
								<input class="product_field product_panel_radio_selector" type="radio" name="review_status" value="2">待审核
								<input class="product_field product_panel_radio_selector" type="radio" name="review_status" value="3">过滤
								<input class="product_field product_panel_radio_selector" type="radio" name="review_status" value="4">侵权
								<input class="product_field product_panel_radio_selector" type="radio" name="review_status" value="5">屏蔽<br/>

					<span style="font-weight: bold;">上架下架:</span>   
								<input class="product_field product_panel_radio_selector" type="radio" name="appear_status" value="1">上架
								<input class="product_field product_panel_radio_selector" type="radio" name="appear_status" value="2">下架
								<input class="product_field product_panel_radio_selector" type="radio" name="appear_status" value="3">失效<br/>

					<span style="font-weight: bold;">安全等级:</span> 
								<input class="product_field product_panel_radio_selector" type="radio" name="security_status" value="1">未分级
								<input class="product_field product_panel_radio_selector" type="radio" name="security_status" value="2">没图案设计
								<input class="product_field product_panel_radio_selector" type="radio" name="security_status" value="3">有图案设计
								<input class="product_field product_panel_radio_selector" type="radio" name="security_status" value="4">国内品牌
								<input class="product_field product_panel_radio_selector" type="radio" name="security_status" value="5">高风险<br/>

					<span style="font-weight: bold;">产品级别:</span> 
								<input class="product_field product_panel_radio_selector" type="radio" name="product_level" value="1">重点
								<input class="product_field product_panel_radio_selector" type="radio" name="product_level" value="2">原创
								<input class="product_field product_panel_radio_selector" type="radio" name="product_level" value="3">海外
								<input class="product_field product_panel_radio_selector" type="radio" name="product_level" value="4">抓取
								<input class="product_field product_panel_radio_selector" type="radio" name="product_level" value="5">导入<br/><br/>

					<span style="font-weight: bold;">产品开发:</span> 
								<select class="product_field product_panel_select_selector" name="developer"><option value="0"></option>{{users_options}}</select>
					<span style="font-weight: bold;">美工修图:</span>  
								<select class="product_field product_panel_select_selector" name="artist"><option value="0"></option>{{users_options}}</select><br/>

					产品品牌:   <input class="product_field product_panel_text_selector" type="text" name="brand"/>
					厂商名称:   <input class="product_field product_panel_text_selector" type="text" name="manufacturer"/>
					厂商编号:   <input class="product_field product_panel_text_selector" type="text" name="manufacturer_id"/>
					原产地区:   <input class="product_field product_panel_text_selector" type="text" name="origin_place"/>
					商品目录:   <input class="product_field product_panel_text_selector" type="text" name="catalog_number"/>
				</div>

				<div id="imageUploader" style="width:45%;height:100%;float:left;">
					<script type="text/javascript">
						$(function(){
							window.imageUploader = new ImageUploader({
								width: "500px",
								height:"300px",
								container: $("#imageUploader"),
								url: "/index/fileUpload",
								id:"uploader",
								isMulti: true,
								isLocal: true,
								preview_img: $("#imageWatcherInstance"),
								onImgClick: function(){
									$("#imageWatcher").fadeIn();
								},
								onclearAll: function(){
									updateVariationImages();
								},
								onclearACard: function(){
									updateVariationImages();
								},
								fileidComposer: function(){
									return UUIDjs.create().hex;
								}
							});
						});
					</script>
				</div>
				
			</div>
			<hr/>

			<div style="width:100%;height:35%;">
				产品标题:<br/><textarea class="product_field product_panel_textarea_selector" rows="2" cols="250" name="title"></textarea><br/>
				关键词:<br/><textarea class="product_field product_panel_textarea_selector" rows="2" cols="250" name="keywords"></textarea>
			</div>
			<hr/>

			<div style="width:100%;height:50%;">
				编辑变体:<button onclick="addAVariation({});">新增</button>
				<table cellspacing="0" cellpadding="0" style="width:90%;text-align: center;">
					<tr style="height:100%;width:100%;">
						<td style="width:10%;height:100%;">变体</td>
						<td style="width:20%;height:100%;">SKU(选填)</td>
						<td style="width:10%;height:100%;">库存</td>
						<td style="width:10%;height:100%;">加价</td>
						<td style="width:40%;height:100%;">图片</td>
						<td style="width:10%;height:100%;">操作</td>
					</tr>
				</table>
				<div style="width:100%;height:80%;overflow-y: auto;">
					<table id="variation_table" border="1" cellspacing="0" cellpadding="0" style="width:90%;text-align: center;vertical-align: middle;"></table>
					<script type="text/javascript">
						function addAVariation(variation){
							var tr = $("<tr style='width:100%;height:50px;'></tr>");
							var name = variation.name || "";
							var SKU = variation.SKU || "";
							var inventory = variation.inventory_count || 0;
							var price_bonus = variation.bonus || "";
							var td_name = $("<td style='width:10%;height:50px;'></td>");
							var input_name = $("<input style='width:80%;height:90%;margin:0;' type='text'>");td_name.append(input_name);
							input_name.attr("name","name");
							input_name.val(name);

							var td_SKU = $("<td style='width:20%;height:50px;'></td>");
							var input_SKU = input_name.clone();td_SKU.append(input_SKU);
							input_SKU.attr("name","SKU");
							input_SKU.val(SKU);

							var td_inventory = $("<td style='width:10%;height:50px;'></td>");
							var input_inventory = input_name.clone();td_inventory.append(input_inventory);
							input_inventory.attr("name","inventory_count");
							input_inventory.val(inventory);

							var td_price_bonus = $("<td style='width:10%;height:50px;'></td>");
							var input_price_bonus = input_name.clone();td_price_bonus.append(input_price_bonus);
							input_price_bonus.attr("name","price_bonus");
							input_price_bonus.val(price_bonus);

							var td_image = $("<td class='variation_image_selector' style='width:40%;height:50px;overflow:hidden;text-align:left;'></td>");
							td_image.prop("fileids",new Array());
							td_image.click(function(){
								showVariationEditPanel(window.imageUploader,{},$(this));
							});

							if(variation.images){
								insertImagesToVariation(td_image,variation.images);
							}
						

							var td_opreation = $("<td style='width:10%;height:50px;'></td>");
							var delete_button = $(document.createElement("button"));
							delete_button.html("删除");
							delete_button.prop("target",tr);
							delete_button.click(function(){
								$(this).prop("target").remove();
							});
							td_opreation.append(delete_button);

							tr.append(td_name);tr.append(td_SKU);tr.append(td_inventory);
							tr.append(td_price_bonus);tr.append(td_image);tr.append(td_opreation);

							$("#variation_table").append(tr);
						}
					</script>
					
				</div>
			</div>
			<hr/>
			<div style="width:100%;height:20%;">
				<span style="font-weight: bold;">海关编码</span>
							<input class="product_field product_panel_text_selector" id="hscode_input" type="text" name="customs_hscode" readonly="true" onfocus="showHscodePanel('hscode_input');" />&nbsp&nbsp
				<span style="font-weight: bold;">英文简称</span>
							<input class="product_field product_panel_text_selector" type="text" name="abbr_en">&nbsp&nbsp
				<span style="font-weight: bold;">中文简称</span>
							<input class="product_field product_panel_text_selector" type="text" name="abbr_cn">&nbsp&nbsp<br/>
				<span style="font-weight: bold;">申报单价</span>
							$<input class="product_field product_panel_text_selector" type="text" name="customs_price">&nbsp&nbsp
				<span style="font-weight: bold">包装毛重</span>
							<input class="product_field product_panel_text_selector" type="text" name="package_weight">g
				<span style="font-weight: bold">包装尺寸</span>
							<input class="product_field product_panel_text_selector" style="width:50px;" type="text" name="package_length">cm x
							<input class="product_field product_panel_text_selector" style="width:50px;" type="text" name="package_width">cm x
							<input class="product_field product_panel_text_selector" style="width:50px;" type="text" name="package_height">cm<br/>
				<span style="font-weight: bold">特殊类型</span>
							<input class="product_field product_panel_checkbox_selector" type="checkbox" name="package_remark" value="purebattery">纯电池
							<input class="product_field product_panel_checkbox_selector" type="checkbox" name="package_remark" value="Li-battery">锂电池
							<input class="product_field product_panel_checkbox_selector" type="checkbox" name="package_remark" value="liquid">液体
							<input class="product_field product_panel_checkbox_selector" type="checkbox" name="package_remark" value="powder">粉末
							<input class="product_field product_panel_checkbox_selector" type="checkbox" name="package_remark" value="magnetic">磁性
							<input class="product_field product_panel_checkbox_selector" type="checkbox" name="package_remark" value="fragile">易碎
							<input class="product_field product_panel_checkbox_selector" type="checkbox" name="package_remark" value="oversized">大件
			</div>
			<hr/>

			<div style="width:100%;height:15%;">
				产品币种:
				<select class="product_field product_panel_select_selector" name="currency">
					<option>人民币</option>
					<option>美元</option>
					<option>欧元</option>
					<option>英磅</option>
					<option>加元</option>
					<option>日元</option>
					<option>澳元</option>
					<option>墨西哥元</option>
					<option>印度卢比</option>
					<option>雷亚尔</option>
				</select>
				&nbsp&nbsp&nbsp&nbsp

				成本单价: <input class="product_field product_panel_select_selector" type="text" name="price">&nbsp&nbsp&nbsp&nbsp
				<input class="product_field product_panel_checkbox_selector" type="checkbox" name="is_distribution"> 
				分销单价: <input class="product_field product_panel_text_selector" type="text" name="distribution_price" value="on"><br/>
				固定运费: <input class="product_field product_panel_text_selector" type="text" name="fixed_shipping">
				<!--国家运费的配置模式暂时不考虑-->
				国家运费: 	<select class="product_field product_panel_select_selector" name="international_shipping_id">
								<option value="0">无挂号费</option>
							</select>
			</div>
			<hr/>

			<div style="width:100%;height:20%;">
				供应商<select class="product_field product_panel_select_selector" name="supplier_id"><option value=""></option>{{suppliers_options}}</select>
				供应货号:<input class="product_field product_panel_text_selector" type="text" name="item_serial_number"/><br/>
				来源网址: <textarea class="product_field product_panel_textarea_selector" style="width:80%;height:25px;" name="resource_url"></textarea><br/>
				附加备注: <textarea class="product_field product_panel_textarea_selector" style="width:80%;height:25px;" name="supply_remark"></textarea>
			</div>
			<hr/>

			<div style="width:100%;">
				适用人群: <select class="product_field product_panel_select_selector" name="design_for_id"><option value=""></option>{{design_for_options}}</select>
				金属类型: <select class="product_field product_panel_select_selector" name="matel_type_id"><option value=""></option>{{metal_type_options}}</select>
				<br/>
				产品材料: <select class="product_field product_panel_select_selector" name="material_type"><option value=""></option>{{material_type_options}}</select>
				包装材料: <input class="product_field product_panel_text_selector" type="text" name="package_material_type"/><br/>
				珠宝类型: <select class="product_field product_panel_select_selector" name="jewel_type_id"><option value=""></option>{{jewel_type_options}}</select>
				戒指尺寸: <select class="product_field product_panel_select_selector" name="ringsize"><option value=""></option>{{ringsize_options}}</select>

			</div>
			<hr/>

			<div style="width:100%;height:8%;">
				内部SKU: <input class="product_field product_panel_text_selector" type="text" name="SKU">
				库存数量: <input class="product_field product_panel_text_selector" type="text" name="product_count">
				每包: <input class="product_field product_panel_text_selector" type="text" name="perpackage_count">
			</div>
			<hr/>

			<div style="width:100%;">
				要点说明:<br/>
				<textarea class="product_field product_panel_textarea_selector" autoheight=true cols="100" name="keypoints"></textarea><br/>
				产品描述:<br/>
				<textarea class="product_field product_panel_textarea_selector" rows="5" cols="100" name="description"></textarea>
			</div>
		</div>
		<div style="width:100%;height:10%;text-align:center;padding-top:10px;">
			<!--button class="btn-primary">缓存表单</button-->
			<button class="btn-primary" onclick="productManager.updateProduct(getProductInstance(),$('#addProductPanel').prop('product_id'));$('#addProductPanel').fadeOut('fast');">提交</button>
			<button class="btn-danger" onclick="$('#addProductPanel').fadeOut();">取消</button>
		</div>
	</div>
	<script type="text/javascript">
		
		function showProductForm(product,isCreate){
			product = product?product:{};
			initProductForm(product);
			$("#addProductPanel").prop("isCreate",isCreate);
			var product_id = isCreate?"0":product.id;
			$("#addProductPanel").prop("product_id",product_id);
			$("#addProductPanel").fadeIn();
		}

		function truncateDatabase(){
			$.post("/product/truncateDatabase/",{id:Math.random()},function(data){
				if(data.success){
					alert("数据库清除成功");
					productManager.refreshProductList(10,0,undefined,undefined,undefined);

				}
			});
		}
		function initProductForm(product){
			$(".product_panel_text_selector").each(function(){
				var content = product[$(this).attr("name")]|| "";
				$(this).val(content);
			});
			$(".product_panel_select_selector").each(function(){
				var content = product[$(this).attr("name")] || "";
				$(this).val(content);
			});
			$(".product_panel_textarea_selector").each(function(){
				var content = product[$(this).attr("name")] || "";
				$(this).val(content);
			});
			$(".product_panel_radio_selector").each(function(){
				var content = product[$(this).attr("name")] || "";
				if($(this).val() === content){
					$(this).prop("checked",true);
				}else{
					$(this).prop("checked",false);
				} 
			});

			
			$(".product_panel_checkbox_selector").each(function(){
				var content = product[$(this).attr("name")];
				if(!content){
					$(this).prop("checked",false);
					return;
				} 
				var contents = content.split("|");
				var isContain = false;
				for(var i = 0; i < contents.length; i++){
					if (contents[i] === $(this).val()){	
						isContain = true;break;
					}
				}

				if(isContain){
					$(this).prop("checked",true);
				}else{
					$(this).prop("checked",false);
				}
			});

			$("#category_options_0").val("");
			$("#category_options_1").val("");
			var categories = window.productManager.getCategoriesById(product['amazon_category_id']);
			for(var level in categories){
				$("#category_options_"+level).val(categories[level]);
				$("#category_options_"+level).change();
			}

			window.imageUploader.clear();
			var images = product['images'];
			if(images){
				for(var i in images){
					imageUploader.addAImgCard(images[i].guid,images[i].url,images[i].file_name);
				}
			}
			$("#variation_table").html("");
			if(product['variation_node']){
				var variations = product['variation_node'];
				for(var i in variations){
					var variation_image = variations[i]['images'];
					for(var j in variation_image){
						var image_id = variation_image[j];
						variation_image[j] = images[image_id];
					}
					addAVariation(variations[i]);
				}
			}
		}

		function getProductInstance(){
			var product = new Object();
			product.images = window.imageUploader.filenames;

			$(".product_panel_text_selector").each(function(){
				var content = $(this).val();
				product[$(this).attr("name")] = content;
			});

			$(".product_panel_select_selector").each(function(){
				var content = $(this).val();
				product[$(this).attr("name")] = content;
			});

			$(".product_panel_textarea_selector").each(function(){
				var content = $(this).val();
				product[$(this).attr("name")] = content;
			});

			$(".product_panel_radio_selector").each(function(){
				if($(this).prop("checked"))	product[$(this).attr("name")] = $(this).val();
			});

			$(".product_panel_checkbox_selector").each(function(){
				if($(this).prop("checked")) {
					product[$(this).attr("name")] = product[$(this).attr("name")]?product[$(this).attr("name")] + "|" + $(this).val() : $(this).val();
				}
			});

			var variations = new Array();
			$("#variation_table tr").each(function(){
				var variation = new Object();
				var tds = $(this).children("td");
				variation.name = $($(tds[0]).children("input")).val();
				variation.SKU = $($(tds[1]).children("input")).val();
				variation.inventory_count = $($(tds[2]).children("input")).val();
				variation.price_bonus = $($(tds[3]).children("input")).val();
				variation.images = $(tds[4]).prop("fileids");
				variations.push(variation);
			});

			product.variations = variations;
			var first_level_category = $("#category_options_0").val();
			var second_level_category = $("#category_options_1").val();
			var categories = [first_level_category];
			if(second_level_category) categories.push(second_level_category);
			var amazon_category_id = window.productManager.getCategoriesContent(categories);
			product.amazon_category_id = amazon_category_id;
			return product;
		}
	</script>
</div>

<div id="hscode_panel" targetInputId="" style="position: absolute;left:0;top:0;background-color:rgba(0,0,0,0.3);width: 100%;height:100%;display:none;">
	<div class="well" style="width:60%;height:90%;margin:0 auto;margin-top:2%;">
		<span style="font-size:30px;">海关编码选择</span><br/>
		<input class="form-control" id="hscode_content" type="text">
		<script type="text/javascript">
			$(function(){
				$("#hscode_content").keypress(function(e,t){
					if(e.key === "Enter"){
						var value = $(this).val();
						loadHsCode(value);
					}
				});
			})
		</script>
		
		<table style="width:90%;margin:0 auto;">
			<tr style="height: 30px;width:100%;">
				<td style="width:40%;height:100%;text-align:center;font-size:25px;">编号</td>
				<td style="width:60%;height:100%;text-align:center;font-size:25px;">商品名称</td>
			</tr>
		</table>
		<hr>
		<div style="width:100%;height:70%;overflow-y: auto;">
			<table id="hscode_table" style="width:90%;margin: 0 auto;"></table>
		</div>
		<div style="text-align: center;">
			<button class="btn-danger" onclick="hideHscodePanel()">取消</button>
		</div>
	</div>
	<script type="text/javascript">
		function addAHscodeLine(hscode){
			var tr = $("<tr style='height:20px;width:100%;cursor:pointer;'><td style='width:40%;height:100%;text-align:center;'>"+hscode.hs_code+"</td><td style='width:60%;height:100%;'>"+hscode.name+"</td></tr>");
			tr.mouseover(function(){
				$(this).css('background-color','cyan');
			});
			tr.mouseout(function(){
				$(this).css('background-color','');
			});

			tr.click(function(){
				var tds = $(this).children();
				var hscode = $(tds[0]).html();
				var targetId = $("#hscode_panel").attr("targetInputId");
				$("#"+targetId).val(hscode);
				hideHscodePanel();
			});
			$("#hscode_table").append(tr);
		}

		function loadHsCode(condition){
			var condition_upload = condition || "";
			var request = {condition:condition_upload};
			$.post("/dataprovider/gethscode",request,function(data){
				if(data.success){
					$("#hscode_table").html("");
					for(var i = 0; i < data.success.length; i++){
						addAHscodeLine(data.success[i]);
					}
				}
			});
		}

		function showHscodePanel(targetInputId){
			loadHsCode();
			$("#hscode_panel").attr("targetInputId",targetInputId);
			$("#hscode_panel").fadeIn();
		}

		function hideHscodePanel(){
			$("#hscode_table").html("");
			$("#hscode_panel").attr("targetInputId","");
			$("#hscode_panel").fadeOut();
		}
	</script>
</div>

<div id="variation_edit_panel" style="position:absolute;left:0;top:0;background-color:rgba(0,0,0,0.3);width:100%;height:100%;display:none;">
	<div style="height:20%;"></div>display
	<div class="well" style="width:60%;height:60%;background-color:white;margin:0 auto;">
		<div id="variation-img-container" style="width:100%;height:90%;"></div>
		<div style="width:100%;height:10%;text-align: center;">
			<button id="confirm-variation-images" class="btn-primary">确定</button>
			<button class="btn-danger" onclick="$('#variation_edit_panel').fadeOut();">取消</button>
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			$("#confirm-variation-images").click(function(){
				var imgs = $("#variation-img-container img");
				var targetTd = $(this).prop("targetTd");
				var fileids = new Array();
				targetTd.html("");
				for(var i = 0; i < imgs.length; i++){
					var img = $(imgs[i]);
					if(!img.prop("container").prop("selected")) continue;
					fileids.push(img.prop("fileid"));
					var imgInstance = $("<img style='max-width:40px;max-height:40px;'/>");
					imgInstance.prop("src",img.prop("src"));
					targetTd.append(imgInstance);
				}
				targetTd.prop("fileids",fileids);
				$("#variation_edit_panel").fadeOut();

			});
		});

		function insertImagesToVariation(targetTd,images){
			var fileids = new Array();
			targetTd.html("");
			for(var i = 0; i < images.length; i++){
				var imgInstance = $("<img style='max-width:40px;max-height:40px;'></img>&nsbp");
				fileids.push(images[i].guid);
				imgInstance.prop("src",images[i].url);
				targetTd.append(imgInstance);
			}
			targetTd.prop("fileids",fileids);
		}

		function updateVariationImages(){
			$(".variation_image_selector").each(function(){
				var fileids = $(this).prop("fileids");
				var imgs = $(this).children("img");
				for(var i = 0; i < fileids.length; i++){
					if(!window.imageUploader.images[fileids[i]]){
						fileids.splice(i,1);
						$(imgs[i]).remove();
					}
				}
			});
		}
		function showVariationEditPanel(imageUploader,variation,targetTd){
			$("#variation-img-container").html("");
			$("#confirm-variation-images").prop("targetTd",targetTd);
			var fileids = targetTd.prop("fileids");
			var images = imageUploader.images;
			for(var fileid in images){
				var img = images[fileid];
				var imgContainer = $("<div style='width:100px;height:100px;float:left;margin: 10px;overflow:hidden;'></div>");
				var table = $("<table cellpadding=0 cellspacing=0 style='width:100px;height:100px;vertical-align:middle;'></table>");
				var tr = $("<tr style='width:100%;height:100%;'></tr>");
				var td = $("<td style='width:100%;height:100%;overflow:hidden;text-align:center;'></td>"); tr.append(td); table.append(tr);

				var imgInstance = $("<img style='max-width:100px;max-height:100px;'/>");
				imgInstance.prop("src",img.prop("src"));
				imgInstance.prop("fileid",img.prop("fileid"));

				var coverDiv = $("<div style='width:100px;height:100px;background-color:rgba(255,255,255,0.6);position:relative;top:-100px;'></div>");
				imgContainer.prop("coverDiv",coverDiv);
				var selected = $.inArray(fileid,fileids) >= 0;
				imgContainer.prop("selected",selected);
				coverDiv.css("display",selected?"none":"");	

				imgInstance.prop("container",imgContainer);

				imgContainer.click(function(){
					$(this).prop("selected",!($(this).prop("selected")));
					$(this).prop("coverDiv").css("display",$(this).prop("selected")?"none":"");
				});

				td.append(imgInstance);
				imgContainer.append(table);
				imgContainer.append(coverDiv);
				$("#variation-img-container").append(imgContainer);
			}

			$("#variation_edit_panel").fadeIn();
		}
	</script>
</div>

<div id="imageWatcher" style="position: absolute;left:0;top:0;background-color: rgba(0,0,0,0.3);width:100%;height:100%;display:none;text-align: center;">
	<div  style="height:10%;width:100%;text-align: right;">
		<div style="width:20px;height:20px;border-radius:10px;background-color: silver;cursor:pointer;float:right;margin-right:50px;text-align:center;" onclick="$('#imageWatcher').fadeOut();">x</div>
	</div>
	<img id="imageWatcherInstance" style="max-width: 80%; max-height: 80%;" />
</div>

<script type="text/javascript">
	$(function(){
		window.productManager = new ProductManager({
			container:$("#productContainer"),
			listUrl:"/dataprovider/listproduct",
			deleteUrl:"/product/deleteproduct",
			createUrl:"/product/createproduct",
			updateUrl:"/product/updateProduct",
			categoryUrl:"/dataprovider/listcategory",
			onEdit: function(product_id){
				$.post("/dataprovider/getProductById",{id:product_id},function(data){
					showProductForm(data.success,false);
				});
			},
			afterCategoryRefresh: function(categories){
				resetCategoriesOptions(categories);
			}
		}).refreshProductList(10,0,undefined,undefined,undefined)
		.refreshCategories();

		$("#category_options_0").change(function(){
			var level1 = $(this).val();
			var sub_level = window.productManager.getCategoriesContent([level1]);
			$("#category_options_1").html("");
			if(sub_level && typeof(sub_level) === "object"){
				for(var key in sub_level){
					$("#category_options_1").append($("<option value='"+key+"'>"+key+"</option>"));
				}
			}

		});
	});

	function resetCategoriesOptions(categories){
		$("#category_options_0").html("");
		$("#category_options_1").html("");
		for(var key in categories){
			$("#category_options_0").append($("<option value='"+key+"'>"+key+"</option>"));
		}
		$("#category_options_0").change();
	}
</script>
