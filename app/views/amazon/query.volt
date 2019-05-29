<!--<!DOCTYPE html>-->
SubmissionId : <input type="text" id="SubmissionId">
<button id="query">查询</button>

<div id="info"></div>
<script type="text/javascript">
	$(function(){
		$("#query").click(function(){
			$.post("/amazon/queryInterface",{submissionId:$("#SubmissionId").val()},function(data){
				$("#info").append("<br/>"+data);
			});
		});
	});
</script>