/**
 * Created by wenhkd on 2017/11/20.
 */
$(function () {

    $('#manage').datagrid({
        url : ThinkPHP['MODULE'] + '/Manage/getList',
        fit : true,
        fitColumns : true,
        striped : true,
        rownumbers : true,
        border : false,
        pagination : true,
        pageSize : 20,
        pageList : [10, 20, 30, 40, 50],
        pageNumber : 1,
        sortName : 'create',
        sortOrder : 'desc',
        toolbar : '#manage_tool',
        columns : [[
            {
                field : 'id',
                title : '编号',
                width : 100,
                checkbox : true,
            },
            {
                field : 'manager',
                title : '管理员帐号',
                width : 100,
            },
            {
                field : 'role',
                title : '所属角色',
                width : 100,
            },
            {
                field : 'create',
                title : '创建时间',
                width : 100,
            },
            {
                field : 'last_login',
                title : '最后登录时间',
                width : 100,
            },
            {
                field : 'last_ip',
                title : '最后登录IP',
                width : 100,
            },
        ]],
    });

    $('#manage_add').dialog({
        width : 350,
        title : '新增管理',
        iconCls : 'icon-user-add',
        modal : true,
        closed : true,
        buttons : [
            {
                text : '提交',
                iconCls : 'icon-add-new',
                handler : function () {
                    if ($('#manage_add').form('validate')) {
                        $.ajax({
                            url : ThinkPHP['MODULE'] + '/Manage/addManage',
                            type : 'POST',
                            data : {
                                manager : $.trim($('input[name="manager"]').val()),
                                password : $('input[name="password"]').val(),
                                role : $.trim($('input[name="role"]').val()),
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
                                        msg : '新增管理成功！',
                                    });
                                    $('#manage_add').dialog('close');
                                    $('#manage').datagrid('load');
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
                    $('#manage_add').dialog('close');
                }
            }
        ],
        onClose : function () {
            $('#manage_add').form('reset');
        },
    });

    $('input[name="manager"]').validatebox({
        required : true,
        validType : 'length[2,20]',
        missingMessage : '请输入管理帐号',
        invalidMessage : '管理帐号必须在2-20位之间',
    });

    $('input[name="password"]').validatebox({
        required : true,
        validType : 'length[6,30]',
        missingMessage : '请输入管理密码',
        invalidMessage : '管理密码在6-30位之间',
    });

    //自动获取用户组,Combobox组合框
    $('#role').combobox({
        url : ThinkPHP['MODULE'] + '/AuthGroup/getListAll',
        required : true,
        editable : false,
        valueField : 'id',
        textField : 'title',
    });

    manage_tool = {
        add : function () {
            $('#manage_add').dialog('open');
            $('input[name="manager"]').focus();
        },
    };
})