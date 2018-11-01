<?php if (!defined('THINK_PATH')) exit();?><table id="manage"></table>

<div id="manage_tool" style="padding:5px;">
  <div style="margin-bottom:5px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add-new" onclick="manage_tool.add();">新增</a>
  </div>
  <div style="padding:0 0 0 7px;color:#333;">
    查询管理：<input type="text" class="textbox" name="search_manage_name" style="width:110px;">
    创建时间从：<input type="text" name="date_from" editable="false" class="easyui-datebox"  style="width:110px;">
    到：<input type="text" name="date_to" editable="false" class="easyui-datebox"  style="width:110px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="alert('自行完成！');">查询</a>
  </div>
</div>

<form id="manage_add" style="margin:0;padding:5px 0 0 25px;color:#333;">
  <p>管理帐号：<input type="text" name="manager" class="textbox" style="width:200px;"></p>
  <p>管理密码：<input type="password" name="password" class="textbox" style="width:200px;"></p>
  <p>分配角色：<input id="role" class="textbox" name="role" style="width:205px;"></p>
</form>


<script type="text/javascript" src="/Tp-weibo/PUBLIC/Admin/js/manage.js"></script>