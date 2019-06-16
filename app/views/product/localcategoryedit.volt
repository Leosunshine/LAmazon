<!--<!DOCTYPE html>-->
<script type="text/javascript" src="/js/jquery.ztree.all.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/ztree/metroStyle/metroStyle.css">
<div style="width:100%;height:90%;overflow-y: auto;">
	<button id="truncateTable">清空分类表</button><span style="color:red;">提示：双击分类修改亚马逊分类</span>
	<script type="text/javascript">
		$(function(){$("#truncateTable").bind("click",function(){$.get("/product/truncatelocalcategory",{},function(data){
			if(data.success){
				alert("清空数据表成功!");
				reloadLocalcategory(data.now);
			}
		});});});
		</script>
	<div id="ztree" class="ztree"></div>
</div>

<div id="edit_panel" style="position: absolute;left:0;top:0;width:100%;height:100%;background-color: rgba(0,0,0,0.3);display: none;">
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

					var child_name = $("#category_name").val();


					var mode = $("#edit_panel").prop("mode");
					if("create" === mode){
						var parent_id = node.id;
						$.post("/product/creatlocalcategory",{parent_id:parent_id,child_name:child_name,amazon_id:amazon_category_id,id:Math.random()},function(data){
							if(data.error){
								alert(data.error);return;
							}

							if(data.success && data.now){
								reloadLocalcategory(data.now);
							}
							$("#edit_panel").prop("treeNode",undefined);
							$("#edit_panel").fadeOut();
						});
					}else if("modify" === mode){
						var targetId = node.id;
						$.post("/product/modifylocalcategory",{targetId:targetId,child_name:child_name, amazon_id:amazon_category_id,id:Math.random()},function(data){
							if(data.error){alert(data.error);return;}
							if(data.success && data.now) reloadLocalcategory(data.now);

							$("#edit_panel").prop("treeNode",undefined);
							$("#edit_panel").fadeOut();
						});
					}
					
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
				dblClickExpand: false,
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
						$("#edit_panel").prop("mode","create");
						$("#edit_panel").fadeIn('fast');
						$("#edit_panel").prop("treeNode",treeNode);
						$("#category_name").val("");
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
			},
			callback:{
				beforeRename: function(treeId, treeNode, newName, isCancel){
					if(isCancel) return true;
					var targetId = treeNode.id;
					$.post("/product/renamelocalcategory",{targetId:targetId,newName:newName});
					return true;
				},
				beforeRemove: function(treeId,treeNode){
					var targetId = treeNode.id;
					var ids = window.getChildenIds(treeNode); //递归获得待删除条目数量
					var isDelete = confirm("是否删除分类"+treeNode.name+"及其所有子分类？\n本操作将对"+ids.length+"种分类进行删除，请谨慎选择");
					ids = ids.join("|");
					if(!isDelete) return false;
					$.post("/product/deletelocalcategory",{ids:ids,id:Math.random()});
					return true;
				},
				onDblClick: function(e,treeId,treeNode){
					var aObj = $("#"+treeNode.tId+"_a");
					aObj.mouseout();
					$("#edit_panel").prop("mode","modify");
					$("#edit_panel").prop("treeNode",treeNode);
					$("#edit_info").html("修改分类  <span style='color:red'>" + treeNode.name+"</span> 基本信息");
					$("#category_name").val(treeNode.name);
					$("#edit_panel").fadeIn("fast");
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

		window.reloadLocalcategory = function(localcategory){
			if(undefined === localcategory) return;
			$.fn.zTree.destroy(window.tree.setting.treeId);
			window.tree = $.fn.zTree.init($("#ztree"),window.zTreeSetting,localcategory);
			window.tree.expandAll(true);
		}

		window.getChildenIds = function(treeNode){
			if(!treeNode.isParent) return [treeNode.id];
			var children = treeNode.children;
			var ret = [treeNode.id];
			for(var index in children){
				var ids = window.getChildenIds(children[index]);
				ret = ret.concat(ids);
			}
			return ret;
		}
	});

	
</script>

