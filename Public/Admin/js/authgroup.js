/**
 * Created by wenhkd on 2017/11/20.
 */
$(function () {

    $('#authgroup').datagrid({
        url : ThinkPHP['MODULE'] + '/AuthGroup/getList',
        fit : true,
        fitColumns : true,
        striped : true,
        rownumbers : true,
        border : false,
        pagination : true,
        pageSize : 20,
        pageList : [10, 20, 30, 40, 50],
        pageNumber : 1,
        toolbar : '#authgroup_tool',
        columns : [[
            {
                field : 'id',
                title : '编号',
                width : 100,
                checkbox : true,
            },
            {
                field : 'title',
                title : '角色名称',
                width : 100,
            },
            {
                field : 'auth',
                title : '拥有的权限',
                width : 100,
            },
        ]],
    });

    $('#authgroup_add').dialog({
        width : 350,
        title : '新增角色',
        iconCls : 'icon-user-add',
        modal : true,
        closed : true,
        buttons : [
            {
                text : '提交',
                iconCls : 'icon-add-new',
                handler : function () {
                    if ($('#authgroup_add').form('validate')) {
                        $.ajax({
                            url : ThinkPHP['MODULE'] + '/AuthGroup/addRole',
                            type : 'POST',
                            data : {
                                title : $.trim($('input[name="title"]').val()),
                                //获取导航
                                rules : $('#auth_nav').combotree('getText'),
                            },
                            beforeSend : function () {
                                $.messager.progress({
                                    text : '正在尝试提交...',
                                });
                            },
                            success : function(data, response, status) {
                                $.messager.progress('close');
                                if (data > 0) {
                                    $.messager.show({
                                        title : '操作提醒',
                                        msg : '新增角色成功！',
                                    });
                                    $('#authgroup_add').dialog('close');
                                    $('#authgroup').datagrid('load');
                                } else if (data == -4) {
                                    $.messager.alert('警告操作', '用户帐号被占用！', 'warning', function () {
                                        $('input[name="username"]').select();
                                    });
                                } else if (data == -5) {
                                    $.messager.alert('警告操作', '电子邮件被占用！', 'warning', function () {
                                        $('input[name="email"]').select();
                                    });
                                } else if (data == -7) {
                                    $.messager.alert('警告操作', '个性域名被占用！', 'warning', function () {
                                        $('input[name="domain"]').select();
                                    });
                                } else if (data == -1 || data == -2 || data == -3 || data == -6) {
                                    $.messager.alert('警告操作', '因某种意外导致前台验证被绕过，请重新刷新提交！', 'warning');
                                } else {
                                    $.messager.alert('警告操作', '未知错误！请重新刷新后提交！', 'warning');
                                }
                            },
                        });
                    }
                }
            },
            {
                text : '取消',
                iconCls : 'icon-redo',
                handler : function () {
                    $('#authgroup_add').dialog('close');
                }
            }
        ],
        onClose : function () {
            $('#authgroup_add').form('reset');
        },
    });

    $('input[name="title"]').validatebox({
        required : true,
        validType : 'length[2,20]',
        missingMessage : '请输入角色名称',
        invalidMessage : '角色名称必须在2-20位之间',
    });

    $('#auth_nav').combotree({
        url : ThinkPHP['MODULE'] + '/Index/getNav',
        required : true,
        lines : true,
        multiple : true,
        checkbox : true,
        onlyLeafCheck : true,
        onLoadSuccess : function (node, data) {
            var _this = this;
            if (data) {
                $(data).each(function () {
                    if (this.state == 'closed') {
                        $(_this).tree('expandAll');
                    }
                })
            }
        },
    });

    authgroup_tool = {
        add : function () {
            $('#authgroup_add').dialog('open');
            $('input[name="title"]').focus();
        },
    };
})