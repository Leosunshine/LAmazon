<!DOCTYPE html>
<html>
<head>
	<title>LAmazon</title>
</head>
<body>
<br/><br/>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/sha1-min.js"></script>
用户行为验证: <input id="username" type="text">
密码: <input type="password" id = "pwd">


卖家编号:<input id="sellerid" type="text" name="sellerID"><br/><br/>
Amazon通行领牌:<input id="token" type="password" name="token"><br/><br/>

<button id="submit">进入</button>


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
	});
</script>
</body>
</html>