<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>商品管理</title>
        <script src="/js/jquery.js"></script>
        
        <script type="text/javascript"  src="/bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/jqGrid/css/pagecss/admin.min.css">
        <link rel="stylesheet" type="text/css" href="/css/ace.min.css">
        <link rel="stylesheet" href="/css/bootstrap.css">

        <script type="text/javascript" src="/js/jquery-ui.js"></script>
        <link rel="stylesheet" type="text/css" href="/jqGrid/css/jquery-ui.min.css">
        <link rel="stylesheet" type="text/css" href="/font-awesome/css/font-awesome.css">
        <script type="text/javascript" src="/js/jquery.ztree.all.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/ztree/metroStyle/metroStyle.css">
        <style type="text/css">
            html,body{
                width:100%;
                height:100%;
                margin:0;
                padding:0;
            }
        </style>
    </head>
    <body>
    <div id="navigation" style="width:100%;height:10%;background-color:yellow;">
        <div style="width:10%;height:100%;float:left;"></div>
        <div style="width:10%;height:100%;font-size: 20px;float:left;cursor:pointer;" onclick="window.location.href='/product'"><div style="height:20%;"></div>产品管理<span class="fa fa-pencil"></span></div>
        <div style="width:10%;height:100%; font-size: 20px;float:left;cursor: pointer;" onclick="window.location.href='/product/localcategoryedit'"><div style="height:20%;"></div>分类管理</div>
        <div style="width:10%;height:100%;font-size: 20px;float:left;cursor:pointer;" onclick="window.location.href='/amazon/amazonupload'"><div style="height:20%;"></div>amazon<br/>上传</span></div>
        <div style="width:10%;height:100%;font-size: 20px;float:left;cursor:pointer;" onclick="window.location.href='/login/logout'"><div style="height:20%;"></div>退出登录</span></div>
    </div>
        <!--div class="container"-->
            {{content()}}
        <!--/div-->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        
        <!-- Latest compiled and minified JavaScript -->
        <!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script-->
    </body>
</html>
