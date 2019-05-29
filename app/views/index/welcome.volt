<script type="text/javascript" src="/js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">
<div id="navigation" style="width:100%;height:10%;background-color:yellow;">导航栏</div>
<div id="downcontent" style="width:100%;height:90%;background-color: pink;">
	<div id="entry" style="width:20%;height:100%;background-color:green;float:left;"></div>
	<div id="content" style="width:80%;height:100%;background-color:silver;float:left;">
		<div id="closify_div" class="closify" style="width:500px;height:100px;">
			<button style="position: absolute;" onclick="$('#fileInput').click();">添加图象</button>
			<input id="fileInput" style="width:50px;height:30px;" class="form-control" type="file" onchange="fileSelected();">
		</div>

		<div id="progressbar" style="width:500px;height:20px;"></div>
	</div>
</div>

<style type="text/css">
	.custom-green{
		color:green;
		background-color: green;
	}

	.custom-radius{
		border-radius: 10px;
	}
</style>
<script type="text/javascript">
	function fileSelected(){
		var file = document.getElementById('fileInput').files[0];
		if(file){
			var fileSize = 0;
			var fileSize = 0;
			if (file.size > 1024 * 1024)
				fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
			else
				fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
			console.log(fileSize);
			uploadFile();
		}
	}

	function uploadFile(){
		var fd = new FormData();
		fd.append("fileInput",document.getElementById('fileInput').files[0]);
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener("progress",uploadProgress,false);
		xhr.open("POST",'/index/fileUpload');
		xhr.send(fd);
	}

	function uploadProgress(evt){
		if(evt.lengthComputable){
			var per = evt.loaded / evt.total * 100;
			$("#progressbar").progressbar("value",per);
		}
	}


	$(function(){
		$("#progressbar").progressbar({
			classes:{
				"ui-progressbar-value":"custom-green",
				"ui-progressbar":"custom-radius"
			},
			value:37
		});
	});
</script>