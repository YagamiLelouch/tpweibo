<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>微博系统--后台管理</title>
    <link rel="stylesheet" type="text/css" href="/Tp-weibo/PUBLIC/Admin/easyui/themes/bootstrap/easyui.css" />
    <link rel="stylesheet" type="text/css" href="/Tp-weibo/PUBLIC/Admin/easyui/themes/icon.css" />
    <style type="text/css">
        .logo {
            width:180px;
            height:50px;
            line-height:50px;
            text-align:center;
            font-size:20px;
            font-weight:bold;
            float:left;
            color:#fff;
        }
        .logout {
            float:right;
            padding:30px 15px 0 0;
            color:#fff;
        }
        .textbox {
            height:20px;
            padding:0 2px;
            position:relative;
            top:-1px;
        }
        a {
            color:#fff;
            text-decoration:none;
        }
        a:hover {
            text-decoration:underline;
        }
        #nav {
            margin:10px 15px;
        }
        .tree-node-selected {
            background:#999;
            border-radius:4px;
        }
        .tree-node-hover {
            border-radius:4px;
        }
        a.tabs-inner {
            color:#666 !important;
        }
        .datagrid-row-selected {
            background:#999 !important;
        }
        .dialog-button {
            text-align:center;
        }
    </style>
    <script type="text/javascript">
        var ThinkPHP = {
            'ROOT' : '/Tp-weibo',
            'MODULE' : '/Tp-weibo/Admin',
            'INDEX' : '<?php echo U("Index/index");?>',
        };
    </script>
</head>
<!--easyui布局-->
<body class="easyui-layout">
<!--顶部-->
<div data-options="region:'north',title:'North Title',split:true,noheader:true" style="height:60px;background:#666;">
    <div class="logo">微博管理</div>
    <div class="logout">您好，<?php echo session('admin')['manager'];?> | <a href="<?php echo U('Login/out');?>">退出</a></div>
</div>
<!--底部-->
<div data-options="region:'south',title:'South Title',split:true,noheader:true" style="height:35px;line-height:30px;text-align:center;">
    ©2009-2014 瓢城 Web 俱乐部. Powered by ThinkPHP and EasyUI.
</div>
<!--左边导航-->
<div data-options="region:'west',title:'导航',split:true,iconCls:'icon-world'" style="width:180px;">
    <ul id="nav"></ul>
</div>
<!--中间标签页和数据表格-->
<div data-options="region:'center'" style="overflow:hidden;">
    <!--标签部分所有-->
    <div id="tabs">
        <!--起始页标签-->
        <div title="起始页" iconCls="icon-house" style="padding:0 10px;">
            <p>欢迎来到微博管理系统！</p>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/Tp-weibo/PUBLIC/Admin/easyui/jquery.min.js"></script>
<script type="text/javascript" src="/Tp-weibo/PUBLIC/Admin/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/Tp-weibo/PUBLIC/Admin/easyui/locale/easyui-lang-zh_CN.js" ></script>
<script type="text/javascript" src="/Tp-weibo/PUBLIC/Admin/js/index.js"></script>
</html>