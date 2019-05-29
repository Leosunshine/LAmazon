<!--<!DOCTYPE html>-->
<div id="supplier-container" style="width:100%;height:100%;background-color: green;">
	<table id="supplier-table"></table>
	<div id="supplier-pager"></div>
</div>
<style type="text/css">
	.ui-icon{
		text-indent: 0 !important;
	}
</style>
<script type="text/javascript">
	$(function(){
		$("#supplier-table").jqGrid({
			url:"/dataprovider/getsuppliers",
			editurl:"/product/updateSupplier",
			datatype:"json",
			height:"430px",
			autowidth:true,
			shrinkToFit:false,
			hidgrid:false,
			colModel:[
				{   name:'id',  label:'id',  index:'id',  width:60, fixed:true, resizable:false, editable:false,sortable:true, sorttype:"int", align:'center',hidden:false},
				{   name:'name',  label:'供应商名称',  index:'name',  width:200, fixed:true, resizable:false, editable:true,sortable:true, sorttype:"string", align:'center',hidden:false},
				{   name:'QQ',  label:'QQ号',  index:'QQ',  width:100, fixed:true, resizable:false, editable:true,sortable:true, sorttype:"int", align:'center',hidden:false},
				{   name:'tel',  label:'电话',  index:'tel',  width:100, fixed:true, resizable:false, editable:true,sortable:true, sorttype:"int", align:'center',hidden:false},
				{   name:'remark',  label:'备注',  index:'remark',  width:300, fixed:true, resizable:false, editable:true,sortable:true, sorttype:"string", align:'center',hidden:false},
			],
			viewrecords: true,
			rowNum:20,
			rowList:[20,40,60],
			pager: "#supplier-pager",
			altRows: true,
			emptyrecords:"<span style='color:red'>还未添加记录</span>",
			reloadAfterSubmit: true,
			caption:"供应商列表",
			multiselect: true,
			multiboxonly: false,
			loadComplete: function(data){
				var table = this;
				setTimeout(function(){
					updatePagerIcons(table);
					enableTooltips(table);
				},0);
			}
		});

		$("#supplier-table").jqGrid("navGrid","#supplier-pager",{
			add:true,
			addicon: "fa fa-plus-circle"
		});
	});
</script>