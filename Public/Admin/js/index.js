$(function () {

    //导航树
    $('#nav').tree({
        url : ThinkPHP['MODULE'] + '/Index/getNav',
        lines : true,
        //服务器端有值返回时执行
        onLoadSuccess : function (node, data) {
            //??
            var _this = this;
            //??
            if (data) {
                $(data).each(function () {
                    if (this.state == 'closed') {
                        //打开子节点,nid为id的节点,执行一次getNav
                        $(_this).tree('expandAll');
                    }
                })
                //??
            } else {
                $('#nav').tree('remove', node.target);
            }
        },
        onClick : function (node) {
            //node里面有url
            if (node.url) {
                //tab已经被打开,跳到打开的节点
                if ($('#tabs').tabs('exists', node.text)) {
                    $('#tabs').tabs('select', node.text)
                    //没有被打开,在后面增加节点,并调用后端
                } else {

                    $('#tabs').tabs('add', {
                        title : node.text,
                        closable : true,
                        iconCls : node.iconCls,
                        href : ThinkPHP['MODULE'] + '/' + node.url,
                    });

                }
            }
        },
    });

    //自适应,无边界
    $('#tabs').tabs({
        fit : true,
        border : false,
    });

});