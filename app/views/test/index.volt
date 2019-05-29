<!--<!DOCTYPE html>-->
<div id="haha" style="width:500px;height:300px;"></div>
<img style="max-width:600px;max-height: 600px;" id="previewImg"/>
<script src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">
<script type="text/javascript" src="/js/jquery-imageuploader.js"></script>
<link rel="stylesheet" type="text/css" href="/font-awesome/css/font-awesome.css">

<button id="test"></button>

<script type="text/javascript">
	$(function(){
		$("#test").click(function(){
			$.ajax({
				url:"/test/test",
				timeout:2000,
				type:"post",
				data:{},
				dataType:'json',
				success:function(data){
				},
				complete:function(xhr,status,data){
					console.log(xhr,status,data);
				}
			});
		});
		
	});
</script>