<!--<!DOCTYPE html>-->
<div style="width:100%;height:90%;overflow-y: auto;">
	<button class="btn btn-danger" id="truncateTable">清空分类表</button><span style="color:red;">提示：双击分类修改亚马逊分类</span>
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

<div id="edit_panel" style="position: absolute;left:0;top:0;width:100%;height:100%;background-color: rgba(0,0,0,0.3);display:none;">
	<div style="height:20%;"></div>
	<div class="well" style="width:80%;height:40%;margin:0 auto;">
		<span id="edit_info"></span><br/>
		新分类名称: <input id="category_name" type="text" name="remark"><br/>
		<!--对应的xsd分类：<br/-->
		
		<select style="display:none;" id="category_options_0"></select>

		<select style="display:none;" id="category_options_1"></select><br/>

		对应的亚马逊分类节点树:<br/>
		<input style="width:60%;" type="text" readonly="true" id="amazon_node_path" onclick="$('#modify_amazon_node').click();">
		<button id="modify_amazon_node">修改</button>
		<script type="text/javascript">
			$(function(){
				$("#modify_amazon_node").click(function(){
					$("#category_path_selected").val($("#amazon_node_path").val());
					var obj = $.fn.zTree.getZTreeObj("selected_ztree");
					if(obj) obj.destroy();
					$("#amazon_node_path_panel").fadeIn();
				});
			});
		</script>
		<hr>
		<button class="btn btn-primary" id="edit_confirm">确定</button>
		<script type="text/javascript">
			$(function(){
				$("#edit_confirm").click(function(){
					var node = $("#edit_panel").prop("treeNode");
					//var amazon_category_first = $("#category_options_0").val();
					//var amazon_category_second = $("#category_options_1").val();

					var amazon_category_first = "Home";
					var amazon_category_second = "Home";

					var amazon_category_id = window.categories[amazon_category_first];
					if(!amazon_category_id){
						alert("出错了,请联系开发人员");
						return;
					}

					if(amazon_category_id[amazon_category_second]){
						if(typeof(amazon_category_id) === 'object'){
							amazon_category_id = amazon_category_id[amazon_category_second];
						}
					}

					var child_name = $("#category_name").val();
					var node_path = $("#amazon_node_path").val();
					var nodeId = $("#amazon_node_path").prop("nodeId");
					if("" === node_path || (!nodeId)){
						alert("亚马逊分类节点树未选取");
						return;
					}

					var mode = $("#edit_panel").prop("mode");
					if("create" === mode){
						var parent_id = node.id;
						$.post("/product/creatlocalcategory",
							{
								parent_id:parent_id,
								child_name:
								child_name,
								amazon_id:amazon_category_id,
								amazon_node_path:node_path,
								amazon_nodeId: nodeId,
								id:Math.random()},function(data){
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
						$.post("/product/modifylocalcategory",
							{
								targetId:targetId,
								child_name:child_name, 
								amazon_id:amazon_category_id,
								amazon_node_path:node_path,
								amazon_nodeId:nodeId,
								id:Math.random()},function(data){
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

<div id="amazon_node_path_panel" style="position: absolute;left:0; top:0; width:100%;height:100%; background-color: rgba(0,0,0,0.5);display:none;">
	<div style="height:10%;"></div>
	<div class="well" style="width:80%;height:80%;margin:0 auto;">
		<input style="width:90%;" id="category_path_selected" type="text" readonly="true" />
		<div style="width:100%;height:80%;">
			<table style="width:100%;height:100%;">
				<tr style="height:100%;">
					<td style="width:50%;height:50%;">
						<div class="ztree" id="first_two_ztree" style="width:95%;height:100%;border-radius:10px;background-color:rgba(0,0,0,0.1);overflow: auto;"></div>
					</td>
					<td class="ztree" style="width:50%;height:50%;">
						<div  id="selected_ztree" style="width: 95%;height:100%;border-radius:10px;background-color:rgba(0,0,0,0.1); overflow: auto;"></div>
					</td>
				</tr>
			</table>
		</div>
		<div style="height:5%;"></div>
		<div style="width:100%;height:10%;text-align: right;">
			<button id="amazon_node_path_confirm" class="btn btn-primary">确定</button>
			<script type="text/javascript">
				$(function(){
					$("#amazon_node_path_confirm").click(function(){
						var path = $("#category_path_selected").val();
						if("" !== path){
							$("#amazon_node_path").val($("#category_path_selected").val());
							$("#amazon_node_path").prop("nodeId",$("#category_path_selected").prop("nodeId"));
						}
						$("#amazon_node_path_panel").fadeOut();
					});
				});
			</script>
			<button onclick="$('#amazon_node_path_panel').fadeOut();" class="btn btn-danger">取消</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	window.zTreeSetting = {
		view:{
			dblClickExpand: false,
			addHoverDom:function(treeId, treeNode){
				var aObj = $("#"+treeNode.tId+"_a");
				if ($("#diyButton_"+treeNode.id).length>0) return;
				var btn =$("<span class='button add' title='添加子分类' id='diyButton_"+treeNode.id+"'></span>");
				btn.prop("treeNode",treeNode);
				btn.prop("treeId",treeId);
				btn.bind("click",function(){
					var treeNode = $(this).prop("treeNode");
					var treeId = $(this).prop('treeId');
					$(this).parent().mouseout();
					window.popupEditPanel({
						mode:"create",
						treeNode:treeNode,
						info:"向分类  <span style='color:red;'>"+treeNode.name+"</span>  添加子分类"
					});
				});
				aObj.append(btn);

			},
			removeHoverDom: function(treeId, treeNode){	
				$("#diyButton_"+treeNode.id).unbind().remove();
			},

		},
		edit:{
			enable:true,
			showRemoveBtn:true,
			showRenameBtn:true,
			removeTitle:"删除本分类",
			renameTitle:"重命名"
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
				if(0 === treeNode.level){
					console.log("根节点不允许编辑");
					$.fn.zTree.getZTreeObj(treeId).cancelEditName();
					return false;
				}
				var targetId = treeNode.id;
				$.post("/product/renamelocalcategory",{targetId:targetId,newName:newName});
				return true;
			},
			beforeRemove: function(treeId,treeNode){
				if(0 === treeNode.level){
					alert("根节点不允许删除操作,若想清空分类表，请点击 清空分类表 按钮进行");
					return false;
				}
				var targetId = treeNode.id;
				var ids = window.getChildenIds(treeNode); //递归获得待删除条目数量
				var isDelete = confirm("是否删除分类"+treeNode.name+"及其所有子分类？\n本操作将对"+ids.length+"种分类进行删除，请谨慎选择");
				ids = ids.join("|");
				if(!isDelete) return false;
				$.post("/product/deletelocalcategory",{ids:ids,id:Math.random()});
				return true;
			},
			onDblClick: function(e,treeId,treeNode){
				if(0 === treeNode.level){
					alert("根节点不允许编辑");
					return false;
				}
				var aObj = $("#"+treeNode.tId+"_a");
				aObj.mouseout();
				window.popupEditPanel({
					mode:"modify",
					treeNode:treeNode,
					info:"修改分类  <span style='color:red'>" + treeNode.name+"</span> 基本信息",
					name:treeNode.name
				});
			}
		}
	};

	window.first_two_setting = {
		edit:{
			enable:false
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
			onClick:function(event,treeId,treeNode){
				if(0 === treeNode.level){
					return;
				}

				if(1 === treeNode.level){
					$("#category_path_selected").val(treeNode.path);
					$("#category_path_selected").prop("nodeId",treeNode.nodeId);
					return;
				}
				var name = treeNode.name_de;
				var parent = treeNode.parent_name;

				$.post("/dataprovider/listAmazonNodes",{id:Math.random(),first:parent,second:name},function(data){
					var nodes = data;
					var obj = $.fn.zTree.getZTreeObj("selected_ztree");
					if(obj) obj.destroy();
					var tree = $.fn.zTree.init($("#selected_ztree"),window.selected_ztree_setting,nodes);
					tree.expandAll(true);
				});
			}
		}
	}
	
	window.selected_ztree_setting = {
		edit:{
			enable:false
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
			onClick: function(event,treeId,treeNode){
				$("#category_path_selected").val(treeNode.path);
				$("#category_path_selected").prop("nodeId",treeNode.nodeId);
			}
		}
	}

	$(function(){
		//加载亚马逊分类
		$.post("/dataprovider/listcategory",{id:Math.random()},function(data, status, jqxhr){
			if(data.success){
				window.categories = data.success;
				window.categoriesDic = data.dictionary;
				window.categoriesMap = new Object();
				for(var key in window.categories){
					if(typeof(window.categories[key]) === "object"){
						for(var index in window.categories[key]){
							window.categoriesMap[window.categories[key][index]] = [key,index];
						}
					}else{
						window.categoriesMap[window.categories[key]] = [key];
					}
				}
				resetAmazoncategory(window.categories);
			}
		});
		//加载本地分类
		$.post("/dataprovider/listlocalcategory",{id:Math.random()},function(data){
			var nodes = data;
			var tree = $.fn.zTree.init($("#ztree"),window.zTreeSetting,nodes);
			window.tree = tree;
		});

		//加载亚马逊分类节点树前两级
		$.post("/dataprovider/listAmazonNodes",{id:Math.random(),max_level:2,"first":"__any",second:"__any"},function(data){
			var nodes = data;
			var tree = $.fn.zTree.init($("#first_two_ztree"),first_two_setting,nodes);
		});

		function resetAmazoncategory(categories){
			$("#category_options_0").html("<option style='display:none' value=''></option>");
			$("#category_options_0").unbind();
			$("#category_options_0").prop("categories",categories);

			for(var first in categories){
				$("#category_options_0").append($("<option value='"+first+"'>"+window.categoriesDic[first]+"</option>"));
			}
			$("#category_options_0").bind("change",function(){
				var categories = $(this).prop("categories");
				var entry = $(this).val();
				$("#category_options_1").html("");
				$("#category_options_1").val("");
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

	function popupEditPanel(options){
		var info = options.info;
		var mode = options.mode || "create";
		var category_name = options.name || "";
		var treeNode = options.treeNode;


		$("#edit_info").html(info);
		$("#edit_panel").prop("mode",mode);
		$("#edit_panel").prop("treeNode",treeNode);
		$("#amazon_node_path").val(treeNode.path);
		$("#category_name").val(category_name);
		$("#edit_panel").fadeIn('fast');

		var parent_amazon_id = treeNode.amazon_category_id;
		var amazon_category = window.categoriesMap[parent_amazon_id+""];
		if(amazon_category){
			$("#category_options_0").val(amazon_category.length > 0? amazon_category[0]: "");
			$("#category_options_0").change();
			$("#category_options_1").val(amazon_category.length <= 1?"":amazon_category[1]);
		}else{
			$("#category_options_0").val("");
			$("#category_options_0").change();
			$("#category_options_1").val("");
		}

		$("#amazon_node_path").val(treeNode.amazon_node_path);
		$("#amazon_node_path").prop("nodeId",treeNode.amazon_nodeId);
}

	
</script>

