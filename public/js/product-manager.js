(function(){
	var ProductManager = function(options){
		this.container = options.container;
		this.listUrl = options.listUrl;
		this.deleteUrl = options.deleteUrl;
		this.createUrl = options.createUrl;
		this.updateUrl = options.updateUrl;
		this.categoryUrl = options.categoryUrl;


		this.beforeCategoryRefresh = options.beforeCategoryRefresh || function(){};
		this.onCategoryRefresh = options.onCategoryRefresh || function(){};
		this.afterCategoryRefresh = options.afterCategoryRefresh || function(){};
		this.onDelete = options.onDelete || function(){};
		this.onEdit = options.onEdit || function(){};
		this.afterListRefresh = options.afterListRefresh || function(total, pageTotal, pageIndex){};

		this.limit = 10; this.offset = 0; this.orderBy = undefined; this.asc = undefined; this.condition = undefined;
	};

	ProductManager.prototype.categories = new Object();
	ProductManager.prototype.categoriesMap = new Object();
	ProductManager.prototype.categoriesDic = new Object();

	ProductManager.prototype.products = new Object();

	ProductManager.prototype.refreshProductList = function(limit,offset, orderBy, asc,condition){
		this.products = new Object();
		this.container.html("");
		orderBy = orderBy || "id";
		asc   = asc || "asc";

		this.limit = limit; this.offset = offset; this.orderBy = orderBy; this.asc = asc; this.condition = condition;

		$.post(this.listUrl,{limit:limit,offset:offset,orderBy:orderBy, asc:asc, condition: condition},function(data,status,jqxhr){
			if(data.success){
				var host = jqxhr.host;
				for(var i in data.success){
					var div = host.createProductCard(data.success[i],$);
					host.container.append(div);
				}
				
				var pageTotal = Math.ceil(data.total/host.limit);
				pageTotal = pageTotal <= 0? 1:pageTotal;
				var pageIndex = Math.floor(host.offset/host.limit) + 1;
				host.afterListRefresh(data.total, pageTotal, pageIndex);
			}
		}).host = this;
		return this;
	}

	ProductManager.prototype.refreshCategories = function(){
		if(!this.categoryUrl) return this;
		this.beforeCategoryRefresh();
		$.post(this.categoryUrl,{id:Math.random()},function(data, status, jqxhr){
			if(data.success){
				var host = jqxhr.host;
				host.setCategories(data.success);
				host.categoriesDic = data.dictionary;
				host.afterCategoryRefresh(host.categories);
			}
		}).host = this;
		this.onCategoryRefresh();
		return this;
	}
	ProductManager.prototype.createProductCard = function(product,$){
		var title = product.title || "unknown";
		var price = product.price || "0.00";
		var currency = product.currency || "$";
		var fixed_shipping = product.fixed_shipping || "0.00";
		var cover = product.cover || "/resources/logo.png";

		var productCardContainer_div = $(document.createElement("div"));
		productCardContainer_div.prop("product_id",product.id);
		productCardContainer_div.addClass("productCardOutter");
		var productCardInner_div = $(document.createElement("idv"));
		productCardInner_div.addClass("productCardInner");
			var imgContainer_div = $(document.createElement("div"));
			imgContainer_div.addClass("imgContainer");
			var table = $("<table cellSpacing=0 cellPadding = 0 style='width:180px;height:180px;margin:0;padding:0;'></table>"); 
			var tr = $("<tr style='width:180px;height:180px;margin:0;padding:0;'></tr>"); 
			var td = $("<td vAlign='middle' style='width:180px;height:180px;margin:0;padding:0;'></td>");
			tr.append(td);table.append(tr);
			

			var imgContent = $(document.createElement("img"));
			imgContent.prop("src",cover);
			imgContent.css("width","100%");
			imgContent.css("height","100%");
			var imgCover = $(document.createElement("div"));
			var editButton = $("<button style='display:none;'>编辑</button>");
			editButton.prop("product_id",product.id);
			editButton.prop("host",this);
			editButton.click(function(){
				var product_id = $(this).prop("product_id");
				var host = $(this).prop("host");
				host.onEdit(product_id);
			});

			var deleteButton = $("<button style='display:none;'>删除</button>");
			deleteButton.prop("product_id", product.id);
			deleteButton.prop("host",this);

			deleteButton.click(function(){
				var product_id = $(this).prop("product_id");
				var isdelete = confirm("您确定要删除编号为"+product_id+"商品吗?");

				if(!isdelete) return;
				var host = $(this).prop("host");
				host.deleteProduct(product_id);
				host.onDelete(product_id);
			});

			imgCover.append(editButton);
			imgCover.append(deleteButton);
			imgCover.addClass("imgContainer_cover");
			imgCover.mouseover(function(){
				$(".imgContainer_cover").css("background-color","rgba(0,0,0,0.001)");
				$(this).css("background-color","rgba(0,0,0,0.1)");
				$($(this).children("button")).show();
			});
			imgCover.mouseout(function(){
				$(".imgContainer_cover").css("background-color","rgba(0,0,0,0.001)");
				$($(this).children("button")).hide();
			});
			td.append(imgContent);
			imgContainer_div.append(table);
			imgContainer_div.append(imgCover);

			var price_div = $(document.createElement("div"));
			price_div.addClass("price_panel");
			price_div.html(currency+"<font style='color:blue;'>"+price+"</font> <span style='font-size:10px;color:grey;'>+"+ fixed_shipping+"</span>");

			var title_div = $(document.createElement("div"));
			title_div.addClass("title_panel");
			title_div.prop("title",title);
			title_div.html(title);

		productCardInner_div.append(imgContainer_div);
		productCardInner_div.append(price_div);
		productCardInner_div.append(title_div);
		productCardContainer_div.append(productCardInner_div);
		return productCardContainer_div;
	}
	ProductManager.prototype.setCategories = function(categories){
		this.categories = categories;
		this.categoriesMap = new Object();
		for(var key in categories){
			if(typeof(categories[key]) === "object"){
				for(var index in categories[key]){
					this.categoriesMap[categories[key][index]] = [key,index];
				}
			}else{
				this.categoriesMap[categories[key]] = [key];
			}
		}
	}

	ProductManager.prototype.getCategoriesContent = function(names){
		$result = this.categories;
		for(var i = 0; i < names.length; i++){
			if(!$result[names[i]]) return undefined;
			$result = $result[names[i]];
		}
		return $result;
	}

	ProductManager.prototype.getCategoriesById = function(id){
		if(id){
			return this.categoriesMap[id];
		}else{
			return new Array();
		}
		
	}
	ProductManager.prototype.deleteProduct = function(id){
		$.post(this.deleteUrl,{id:id,random:Math.random()},function(data,status,jqxhr){
			if(data.success){
				var host = jqxhr.host;
				host.refreshProductList(host.limit, host.offset, host.orderBy, host.asc, host.condition);
			}
		}).host = this;
	}

	//方法弃用,改用updateProduct方法中置product_id为0设定为新建
	ProductManager.prototype.createProduct = function(product){
		var request = {
			product: JSON.stringify(product),
			id:Math.random()
		};

		$.post(this.createUrl,request,function(data,status,jqxhr){
			if(data.success){
				alert("商品新建成功,新商品的编号为: "+ data.id);
				$("#addProductPanel").fadeOut('fast');
				var host = jqxhr.host;
				host.refreshProductList(host.limit,host.offset,host.orderBy,host.asc,host.condition);
			}else{
				alert("数据储存出错");
			}
		}).host = this;
	}

	ProductManager.prototype.updateProduct = function(product,product_id){
		var request = {
			product: JSON.stringify(product),
			id:product_id,
			rand: Math.random()
		};
		$.post(this.updateUrl,request,function(data,status,jqxhr){
			if(data.success){
				alert("商品信息提交成功,商品编号为: " + data.success);
			}else{
				alert("数据储存出错");
			}
			$("#addProductPanel").fadeOut('fast');
			var host = jqxhr.host;
			host.refreshProductList(10,0,undefined,undefined,undefined);
		}).host = this;
	}

	window.ProductManager = ProductManager;
}());