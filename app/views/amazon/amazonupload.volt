<!--<!DOCTYPE html>-->
<br/>
<div style="width:50%;height:100%;float:left;">
	<button id="refreshButton" class="btn btn-primary">刷新</button>
	<script type="text/javascript">
		$(function(){
			$("#refreshButton").click(function(){
				$.post("/amazon/getpreparedcount",{id:Math.random()}, function(data){
					if(data.success){
						var products = data.success.products;
						$("#uploadProductInfo").html("共有 "+products.add+" 个产品需新建, " +products.update+ " 个产品需修改, " +products.delete+" 个产品需删除");

						$("#uploadRelationInfo").html("共有" + data.success.relation + "组商品需上传变体关系");

						$("#uploadPriceInfo").html("共有" + data.success.price + "个商品需上传价格信息");

						$("#uploadInventoryInfo").html("共有" + data.success.inventory + "个商品需要上传库存信息");
					}
				});
			});

			$("#refreshButton").click();
		});
	</script>
	<hr>
	<button id="updateProduct" class="btn btn-primary">上传产品信息</button>
	<span id="uploadProductInfo"></span>
	<hr>

	<button id="updateRelationship" class="btn btn-primary">绑定父商品与变体</button>
	<span id="uploadRelationInfo"></span>
	<hr/>

	<button id="updatePrice" class="btn btn-primary">上传产品价格信息</button>
	<span id="uploadPriceInfo"></span>
	<hr/>
	<button id="updateInventory" class="btn btn-primary">上传产品库存信息</button>
	<span id="uploadInventoryInfo"></span>
	<hr/>
	<button id="updateImages" class="btn btn-primary">上传产品图片信息</button>
	<span id="uploadImageInfo"></span>
	<hr/>
	<button style="display: none;" id="updateShipping" class="btn btn-primary">上传所有产品物流信息</button><span id="updateShippingInfo" style="display: none;"></span><br/><br/>
</div>

<div style="width:48%;height:100%;float:left;">
	SubmissionId : <input type="text" id="SubmissionId" >
	<button id="query">查询</button>
	<div id="info"></div>
</div>

<script type="text/javascript">
	$(function(){
		$("#query").click(function(){
			$.post("/amazon/queryInterface",{submissionId:$("#SubmissionId").val()},function(data){
				$("#info").html(data);
			});
		});
	});
</script>
<script type="text/javascript">
	$(function(){
		//上传所有产品信息到亚马逊, 只有当这个完成，且已经成功的情况下，才可以进行后续的操作
		$("#updateProduct").bind("click",function(){
			$.post("/amazon/updateproducts",{id:Math.random()},function(data){
				if(data.success){
					$("#uploadProductInfo").html(data.success);
					$("#SubmissionId").val(data.success);
				}else{
					console.log(data.error);
				}
			});
		});

		$("#updatePrice").bind("click",function(){
			$.post("/amazon/updatePrices",{id:Math.random()},function(data){
				if(data.success){
					$("#uploadPriceInfo").html(data.success);
					$("#SubmissionId").val(data.success);
				}
			});
		});

		$("#updateInventory").bind("click",function(){
			$.post("/amazon/updateInventory",{id:Math.random()},function(data){
				if(data.success){
					$("#uploadInventoryInfo").html(data.success);
					$("#SubmissionId").val(data.success);
				}
			});
		});


		$("#updateImages").bind("click",function(){
			$.post("/amazon/updateImages",{id:Math.random()},function(data){
				if(data.success){
					$("#uploadImagesInfo").html(data.success);
					$("#SubmissionId").val(data.success);
				}
			});
		});

		$("#updateRelationship").bind("click",function(){
			$.post("/amazon/updateRelationship",{id:Math.random()},function(data){
				if(data.success){
					$("#uploadRelationshipInfo").html(data.success);
					$("#SubmissionId").val(data.success);
				}
			});
		});
		$("#updateShipping").bind("click",function(){
			return;
			//取消上传物流信息功能
			// $.post("/amazon/updateShipping",{id:Math.random()},function(data){
			// 	if(data.success){
			// 		$("#updateShippingInfo").html(data.success);
			// 		$("#SubmissionId").val(data.success);
			// 	}
			// });
		});
	});
</script>