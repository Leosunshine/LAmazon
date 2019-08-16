<!DOCTYPE html>
<html>
<head>
	<title>LAmazon 登录</title>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/sha1-min.js"></script>
	<style type="text/css">
		html,body{
			width:100%;height:100%;
			margin: 0; padding: 0;
		}
	</style>
</head>
<body style="background: url(./resources/background.bmp);">
<br/><br/>
<div style="height:20%;"></div>
<div style="color:white;width:40%;height:35%;background-color:rgba(202,133,106,0.95);margin: 0 auto;text-align: center;font-size: 20px;border-radius: 5px;">
	<div style="height:30%;"></div>
	用户: <input  style="font-size: 20px;" id="username" type="text"><br/><br/>
	密码: <input style="font-size: 20px;" type="password" id = "pwd"><br/><br/>
	<button style="font-size: 20px;" id="submit">进入</button>
</div>

<div style="display:none;">
卖家编号:<input hidden="true" id="sellerid" type="text" name="sellerID"><br/><br/>
Amazon通行领牌:<input hidden="true" id="token" type="password" name="token"><br/><br/>
</div>



<script type="text/javascript">
	$(function(){
		$("#submit").click(function(){
			var sellerId = $("#sellerid").val();
			var token = $("#token").val();

			// $.post("/login/setSeller",{sellerId:sellerId,token:token},function(data){
			// 	if(data.error){
			// 		alert(data.error);
			// 	}else{
			// 		if(data.success){
			// 			window.location.href = data.success;
			// 		}
			// 	}
			// });
			var pwd = $("#pwd").val();
			pwd = hex_sha1(pwd);
			$.post("/login/verifyUser",{username:$("#username").val(),password:pwd,id:Math.random()},function(data){
				if(data.success){
					alert("登录成功");
					window.location.href = "/product/index";
				}else{
					alert("用户名或密码错误");
				}
			});
		});

		$("body").keyup(function(e){
			if(e.keyCode != 13) return;
			$("#submit").click();
		});
	});
</script>
</body>
</html>