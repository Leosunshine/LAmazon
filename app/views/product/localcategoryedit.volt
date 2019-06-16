<!--<!DOCTYPE html>-->
<script type="text/javascript" src="/js/jquery.ztree.all.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/ztree/metroStyle/metroStyle.css">
<div style="width:100%;height:90%;overflow-y: auto;">
	<div id="ztree" class="ztree"></div>
</div>

<div id="edit_panel" style="position: absolute;left:0;top:0;width:100%;height:100%;background-color: rgba(0,0,0,0.3);">
	<div style="height:20%;"></div>
	<div class="well" style="width:80%;height:40%;margin:0 auto;">
		<span id="edit_info"></span><br/>
		新分类名称: <input id="category_name" type="text" name="remark"><br/>
		对应的亚马逊分类：<br/>
		<select id="category_options_0"></select><br/>
		<select id="category_options_1"></select><br/>
		<hr>
		<button class="btn btn-primary" id="edit_confirm">确定</button>
		<script type="text/javascript">
			$(function(){
				$("#edit_confirm").click(function(){
					var node = $("#edit_panel").prop("treeNode");

					var parent_id = node.id;
					var child_name = $("#category_name").val();
					var amazon_category_first = $("#category_options_0").val();
					var amazon_category_second = $("#category_options_1").val();

					var amazon_category_id = window.categories[amazon_category_first];
					if(!amazon_category_id){
						alert("出错了,请联系开发人员");
						return;
					}

					if(amazon_category_id[amazon_category_second]){
						if(typeof(amazon_category_id) === 'object'){
							amazon_category_id = amazon_category_id[amazon_category_second];
						}
					}else{
						return;
					}

					$.post("/product/creatlocalcategory",{parent_id:parent_id,child_name:child_name,amazon_id:amazon_category_id},function(data){
						if(data.error){
							alert(data.error);return;
						}

						if(data.success && data.now){
							$.fn.zTree.destroy(window.tree.setting.treeId);
							window.tree = $.fn.zTree.init($("#ztree"),window.zTreeSetting,data.now);
							window.tree.expandAll(true);
						}
						$("#edit_panel").prop("treeNode",undefined);
						$("#edit_panel").fadeOut();
					});
				});
			});
		</script>
		<button class="btn btn-danger" onclick="$('#edit_panel').fadeOut('slow');">取消</button>
	</div>
</div>
<script type="text/javascript">
	
	$(function(){
		window.zTreeSetting = {
			view:{
				addHoverDom:function(treeId, treeNode){
					var aObj = $("#"+treeNode.tId+"_a");
					if ($("#diyButton_"+treeNode.id).length>0) return;
					var btn =$("<button id='diyButton_"+treeNode.id+"'>添加子分类</button>");
					btn.prop("treeNode",treeNode);
					btn.prop("treeId",treeId);
					btn.bind("click",function(){
						var treeNode = $(this).prop("treeNode");
						var treeId = $(this).prop('treeId');
						$(this).parent().mouseout();
						$("#edit_panel").fadeIn('fast');
						$("#edit_panel").prop("treeNode",treeNode);
						$("#edit_info").html("向分类  <span style='color:red;'>"+treeNode.name+"</span>  添加子分类");
					});
					aObj.append(btn);

				},
				removeHoverDom: function(treeId, treeNode){	
					$("#diyButton_"+treeNode.id).unbind().remove();
				}
			},
			edit:{
				enable:true,
				showRemoveBtn:true,
				showRenameBtn:true
			},
			data:{
				simpleData:{
					enable:true,
					idKey:"id",
					pIdKey:"parent_id",
					rootPId: "0"
				}
			}
		};

		$.post("/dataprovider/listcategory",{id:Math.random()},function(data, status, jqxhr){
			if(data.success){
				window.categories = data.success;
				window.categoriesDic = data.dictionary;
				resetAmazoncategory(window.categories);
			}
		});

		$.post("/dataprovider/listlocalcategory",{id:Math.random()},function(data){
			var nodes = data;
			var tree = $.fn.zTree.init($("#ztree"),window.zTreeSetting,nodes);
			tree.expandAll(true);
			window.tree = tree;
		});

		function resetAmazoncategory(categories){
			$("#category_options_0").html("");
			$("#category_options_0").unbind();
			$("#category_options_0").prop("categories",categories);
			for(var first in categories){
				$("#category_options_0").append($("<option value='"+first+"'>"+window.categoriesDic[first]+"</option>"));
			}
			$("#category_options_0").bind("change",function(){
				var categories = $(this).prop("categories");
				var entry = $(this).val();
				$("#category_options_1").html("");
				if(categories[entry] && typeof(categories[entry]) === 'object'){
					for(var second in categories[entry]){
						$("#category_options_1").append($("<option value='"+second+"'>"+window.categoriesDic[second]+"</option>"));
					}
				}
			});
			$("#category_options_0").change();
		}
	});

	
</script>

