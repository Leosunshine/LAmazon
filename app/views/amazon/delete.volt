<!--<!DOCTYPE html>-->
SKU : <input type="text" id="SKU">
<button id="delete">删除</button>

<div id="info"></div>
<script type="text/javascript">
	$(function(){
		$("#delete").click(function(){
			$.post("/amazon/deleteInterface",{SKU:$("#SKU").val()},function(data){
				$("#info").append("<br/>"+data);
			});
		});
	});
</script>