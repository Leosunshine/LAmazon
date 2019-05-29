/*

<div style="overflow-y: auto; width:48%;height:100%;">
				<div id="add_covers" style="border:1px solid red;width:95%;height:95%;float:left;margin-left:20px;overflow-y: auto;">

					<div style="width:100px;height:100px;margin:5px;float:left;">
						<div style="height:20px;"></div>
						<div style="width:60px;height:60px;margin:0 auto;overflow:hidden;border: 1px dashed silver;cursor:pointer;">
							<div style="height:10px;"></div>
							<div style="width:58px;height:58px;text-align:center;margin:0;" onclick="$('#addImageInput').click()">
								<div style="height:10px;"></div>
								+
							</div>
							<input id="addImageInput" style="width:0px;height:0px;margin:0;padding:0;" type="file" id="file" accept="image/png, image/jpeg" onchange="uploadTempImage()">
						</div>
					</div>
					
					<script type="text/javascript">
						function uploadTempImage(){
							var file = document.getElementById("addImageInput").files[0];
							if(!file) return;
							if(file.size > 50 * 1024 * 1024){
								alert("文件大小不得大于50MB");
								return;
							}
							var cover = addALoadingProgress();
							var cover_div = cover.out;
							$("#add_covers").append(cover_div);

							var fd = new FormData();
							fd.append("fileInput",file);
							fd.append("id",Math.random());
							var xhr = new XMLHttpRequest();
							xhr.upload._custome_progressbar = cover.in;

							xhr.upload.addEventListener("progress",function(evt){
								var per = evt.loaded / evt.total * 100;
								this._custome_progressbar.progressbar("value",per);
							},false);
							xhr.onload = function(evt){
								var filename = this.response;
								var img = $("<img style='max-width:100%;max-height:100%;' src='/img/"+filename+"'/>");
								img.click(function(){
									$("#imageWatcherInstance").prop("src",img.prop("src"));
									$("#imageWatcher").fadeIn();
								});
								this.upload._custome_progressbar.replaceWith(img);
								img.attr("filename",filename);
							};
							xhr.open("POST",'/index/fileUpload');  
							//将图片上传至cache文件夹,等上传提交表单后，移动文件至img下该产品id下.(后台产品都应该对应一个自己的resource_id)
							//文件上传
							xhr.send(fd);
							document.getElementById("addImageInput").value = "";
						}



						function addALoadingProgress(){
							var cover_div = $("<div style='width:100px;height:100px;margin:5px;float:left;'></div>");
							var progressbar_div = $("<div style='width:80%;height:10px;margin:0 auto;margin-top:50%;'></div>");
							progressbar_div.progressbar();
							cover_div.append(progressbar_div);

							return {out:cover_div,in:progressbar_div};
						}
						$(function(){
							$("#addImageInput").change(function(){
								var files = document.getElementById("addImageInput").files;
								for(var i = 0; i <files.length; i++){

								}

							});
						});
					</script>
				</div>
				</div>

*/