<table id="authgroup"></table>

<div id="authgroup_tool" style="padding:5px;">
  <div style="margin-bottom:5px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add-new" onclick="authgroup_tool.add();">新增</a>
  </div>
  <div style="padding:0 0 0 7px;color:#333;">
    查询角色：<input type="text" class="textbox" name="search_role_name" style="width:110px;">
    创建时间从：<input type="text" name="date_from" editable="false" class="easyui-datebox"  style="width:110px;">
    到：<input type="text" name="date_to" editable="false" class="easyui-datebox"  style="width:110px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="alert('自行完成！');">查询</a>
  </div>
</div>

<form id="authgroup_add" style="margin:0;padding:5px 0 0 25px;color:#333;">
  <p>角色名称：<input type="text" name="title" class="textbox" style="width:200px;"></p>
  <p>权限分配：<input type="text" id="auth_nav" name="rules" class="textbox" style="width:205px;"></p>
</form>


<script type="text/javascript" src="__JS__/authgroup.js"></script>