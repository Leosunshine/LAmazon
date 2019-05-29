<!DOCTYPE html>
<html>
<head>
	<title>LAmazon</title>
</head>
<body>
<br/><br/>
卖家编号:<input id="sellerid" type="text" name="sellerID"><br/><br/>
Amazon通行领牌:<input id="token" type="password" name="token"><br/><br/>

<button id="submit">进入</button>


<script type="text/javascript">
	$(function(){
		$("#submit").click(function(){
			var sellerId = $("#sellerid").val();
			var token = $("#token").val();

			$.post("/index/setSeller",{sellerId:sellerId,token:token},function(data){
				if(data.error){
					alert(data.error);
				}else{
					if(data.success){
						window.location.href = data.success;
					}
				}
			});
		});
	});
</script>
</body>
</html>