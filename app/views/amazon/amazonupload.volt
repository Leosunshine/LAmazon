<!--<!DOCTYPE html>-->
<br/>
<button id="updateProduct" class="btn btn-primary">上传所有产品信息</button><br/><br/>
<span id="uploadProductInfo">此处显示上传SubmissionId</span><br/><br/>

<hr>
<button id="updatePrice" class="btn btn-primary">上传所有产品价格信息</button><span id="updatePriceInfo"></span><br/><br/>
<button id="updateInventory" class="btn btn-primary">上传所有产品库存信息</button><span id="updateInventoryInfo"></span><br/><br/>
<button id="updateImages" class="btn btn-primary">上传所有产品图片信息</button><span id="updateImagesInfo"></span><br/><br/>
<button style="display: none;" id="updateShipping" class="btn btn-primary">上传所有产品物流信息</button><span id="updateShippingInfo" style="display: none;"></span><br/><br/>

SubmissionId : <input type="text" id="SubmissionId" >
<button id="query">查询</button>
<div id="info"></div>
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
				}
			});
		});

		$("#updatePrice").bind("click",function(){
			$.post("/amazon/updatePrices",{id:Math.random()},function(data){
				if(data.success){
					$("#updatePriceInfo").html(data.success);
					$("#SubmissionId").val(data.success);
				}
			});
		});

		$("#updateInventory").bind("click",function(){
			$.post("/amazon/updateInventory",{id:Math.random()},function(data){
				if(data.success){
					$("#updateInventoryInfo").html(data.success);
					$("#SubmissionId").val(data.success);
				}
			});
		});


		$("#updateImages").bind("click",function(){
			$.post("/amazon/updateImages",{id:Math.random()},function(data){
				
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