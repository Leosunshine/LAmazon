<!--<!DOCTYPE html>-->
<script type="text/javascript" src="/js/jquery.ztree.all.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/ztree/metroStyle/metroStyle.css">
<div style="width:100%;height:90%;">
	<div id="ztree" class="ztree"></div>
</div>

<script type="text/javascript">
	$(function(){
		var nodes = [
			{name: "父节点1", children: [
				{name: "子节点1"},
				{name: "子节点2"}
			]}
		];

		var setting = {

		}

		var tree = $.fn.zTree.init($("#ztree"),setting,nodes);
	});

	
</script>

