<!--<!DOCTYPE html>-->
<!DOCTYPE html>
<html>
<head>
	<title>个人中心</title>
	<style type="text/css">
		html,body{margin: 0;padding: 0; width:100%;height:100%;}
		.entry{
			width:80%;
			margin:0 auto;
			height:5%;text-align:center;font-family: '微软雅黑'; font-size: 20px;
		}
		.entry:hover{
			cursor: pointer;
			background-color: rgba(202,133,106,0.5);
		}

		.editPanel{
			width:100%;
			height:100%;
			overflow: auto;
		}
	</style>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/sha1-min.js"></script>
</head>
<body>
<div style="width:100%;height:10%;background-color: cyan;overflow: hidden;">
	<div style="width:100%;height:30%;float:left;"></div>
	<div style="width:20%;height:100%;float:left;font-size:30px;text-align: center;">个人中心</div>
	<div style="width:20%;height:100%;float:right;font-size:30px;text-align: right;">一些选项</div>
</div>

<script type="text/javascript">
	$(function(){
		$(".entry").bind("click",function(){
			$(".entry").css("background-color","");
			$(this).css("background-color","rgba(202,133,106,0.8)");
			$(".editPanel").hide();
			var panel = $(this).attr('panel');
			$("#"+panel).show();
		});

		window.loadShopInfo = function(){
			$.post("/dataprovider/getshopinfo",{id:Math.random()}, function(data){
				if(data.success){
					$(".shopInfoInputSelector").each(function(){
						var name = $(this).attr("name");
						$(this).val(data.success[name]);
					});
				}
			});
		};

		$("#submitModifies").bind("click",function(){
			var data = new Object();
			$(".shopInfoInputSelector").each(function(){
				var name = $(this).attr("name");
				data[name] = $(this).val();
			});

			console.log(data);

			$.post("/user/updateshop",{data: JSON.stringify(data), id: Math.random()},function(data){
				if(!data.success){
					console.log(data.error);
				}else{
					alert("提交成功");
				}
			});
		});

		$("#submitPwd").bind("click",function(){
			var oldpwd = $("#oldpwd").val();
			oldpwd = hex_sha1(oldpwd);
			var newpwd = $("#newpwd").val();
			var confirmpwd = $("#confirmpwd").val();
			if(newpwd != confirmpwd){
				alert("两次输入新密码不一致");
				return;
			}
			newpwd = hex_sha1(newpwd);
			$.post("/user/updatepwd",{oldpwd:oldpwd,newpwd:newpwd,id:Math.random()},function(data){
				if(data.error){
					alert(data.error);
				}else if(data.success){
					alert("密码修改完成");
				}else{
					alert("发生未知错误");
				}
			});

		});

		window.loadShopInfo();
	});
</script>
<div style="width:100%;height:90%;overflow: hidden;">
	<div style="width:20%;height:100%;float:left;">
		<div style="height:5%;"></div>
		<div style="width:80%;margin:0 auto;height:auto;text-align: center;">
			<img src="/resources/usericon.jpg" style="max-height: 100%;max-width: 100%;border-radius: 50%; cursor: pointer;">
		</div>
		<div style="width:100%;height:10%;"></div>
		<div class="entry" style="background-color: rgba(202,133,106,0.8);" panel = 'shopInfoEditPanel'>修改店铺信息</div>
		<div class="entry" panel = 'securityEditPanel'>修改密码</div>
	</div>
	<div style="width:80%;height:100%;float:left;">
		<div style="width:100%;height:1%"></div>
		<div style="width:98%;height:99%;margin: 0 auto;overflow: hidden;">
			<div class="editPanel" id="shopInfoEditPanel">
				<input class="shopInfoInputSelector" hidden="true" type="text" name="id">
				店铺名称:     <input style="width:400px;" class="shopInfoInputSelector" type="text" name="name"><br/>
				亚马逊商户ID: <input style="width:400px;" class="shopInfoInputSelector" type="text" name="amazon_merchant_id"><br/>
				亚马逊MWS授权令牌: <input style="width:400px;" class="shopInfoInputSelector" type="text" name="amazon_token"><br/>
				<button id="submitModifies">提交</button>
				<button id="resetForm" onclick="loadShopInfo();">重置</button>
			</div>
			<div class="editPanel" id="securityEditPanel">
				原始密码  :<input id="oldpwd" type="password" name="oldpwd"><br/>
				新密码    :<input id="newpwd" type="password" name="newpwd"><br/>
				确认新密码:<input id="confirmpwd" type="password" name="confirmpwd"><br/>
				<button id="submitPwd">提交修改</button>
			</div>
		</div>
	</div>
</div>
</body>
</html>
