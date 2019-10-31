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

		body{
			background:linear-gradient(white,cyan);
		}
	</style>
</head>
<body>
<br/><br/>
<div style="height:5%;"></div>
<div style="height:35%;text-align: center;">
	<img style="max-width:80%;max-height:80%;" src="/resources/logo.png"><br/>
	LAMAZON
</div>
<div style="color:white;width:40%;height:35%;background-color:rgba(202,133,106,0.85);margin: 0 auto;text-align: center;font-size: 20px;border-radius: 5px;">
	<div style="height:30%;"></div>
	用户: <input  style="font-size: 20px;" id="username" type="text"><br/><br/>
	密码: <input style="font-size: 20px;" type="password" id = "pwd"><br/><br/>
	<button style="font-size: 20px;" id="submit">进入</button>
	<!--button style="font-size: 20px;" id="signup">注册</button-->
</div>

<script type="text/javascript">
	$(function(){
		$("#submit").click(function(){
			var sellerId = $("#sellerid").val();
			var token = $("#token").val();
			var pwd = $("#pwd").val();
			pwd = hex_sha1(pwd);
			$.post("/login/verifyUser",{username:$("#username").val(),password:pwd,id:Math.random()},function(data){
				if(data.success){
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