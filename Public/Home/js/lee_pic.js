/**
 * Created by wenhkd on 2017/10/29/029.
 */
/*
 * 微博配图上传JS插件
 * */
$(function () {
    var lee_pic = {
        //已经上传的图片数量
        uploadTotal : 0,
        //还可以上传的图片数
        uploadLimit : 8,
        //上传方法
        uploadify : function () {
            //文件上传测试
            $('#file').uploadify({
                swf : ThinkPHP['UPLOADIFY'] + '/uploadify.swf',
                //传到File/upload
                uploader : ThinkPHP['IMAGEURL'],
                width : 120,
                height : 35,
                fileTypeDesc : '图片类型',
                buttonCursor : 'pointer',
                buttonText : '上传图片',
                fileTypeExts : '*.jpeg; *.jpg; *.png; *.gif',
                fileSizeLimit:'1MB',
                overrideEvents:['onSelectError','onSelect','onDialogClose'],
                onSelectError:function (file, errorCode, errorMsg) {
                    switch(errorCode){
                        case -110:
                            $('#error').dialog('open').html('over 1024kb');
                            setTimeout(function () {
                                $('#error').dialog('close').html('...');
                            },1000);
                            break;
                    }
                },
                //点击图片上传按钮，选择图片后点确定执行
                onUploadStart : function () {
                //当总上传图片数超过8时中断继续增加图片（上传图片数量张数为（lee_pic.uploadTotal-1）-1张
                    if (lee_pic.uploadTotal == 8) {
                        $('#file').uploadify('stop');
                        $('#file').uploadify('cancel');
                        $('#error').dialog('open').html('限制为8张...');
                        setTimeout(function () {
                            $('#error').dialog('close').html('...');
                        }, 1000);
                    } else {
                        $('.weibo_pic_list').append('<div class="weibo_pic_content"><span class="remove"></span><span class="text">删除</span><img src="' + ThinkPHP['IMG'] + '/loading_100.png" class="weibo_pic_img"></div>');
                    }
                },

                //成功返回数据后执行
                onUploadSuccess : function (file, data, response) {
                    $('.weibo_pic_list').append('<input type="hidden" name="images" value=' + data + '>')
                    //jQuery.parseJSON( json ) 接受一个标准格式的 JSON 字符串，并返回解析后的 JavaScript 值。
                    var imageUrl = $.parseJSON(data);
                    //处理缩略图
                    lee_pic.thumb(imageUrl['thumb']);
                    //将二个事件函数绑定到匹配元素上，分别当鼠标指针进入和离开元素时被执行。
                    lee_pic.hover();
                    //调用remove()
                    lee_pic.remove();
                    //上传的图片数量增加
                    lee_pic.uploadTotal++;
                    //还可以上传的图片数减少
                    lee_pic.uploadLimit--;
                    $('.weibo_pic_total').text(lee_pic.uploadTotal);
                    $('.weibo_pic_limit').text(lee_pic.uploadLimit);
                }
            });
        },

        //缩略图进行统一大小
        thumb:function (src) {
            var img=$('.weibo_pic_img');
            var len=img.length;
            //先隐藏图片
            $('img[len-1]').attr('src',src).hide();
            setTimeout(function () {
                if($(img[len-1]).width()>100){
                    //已经对img进行了position,所以可以设置定位.对pic_content进行了溢出隐藏,所以显示区域固定
                    $(img[len-1]).css('left',-($(img[len-1]).width()-100)/2);
                }
                if ($(img[len - 1]).height() > 100) {
                    $(img[len - 1]).css('top', -($(img[len - 1]).height() - 100) / 2);
                }
                //让图片慢慢显现,ThinkPHP['ROOT'] + src要加,因为进入网站有两种方式,两种方式路径不一样,具体原因还不清楚,有待研究
                $(img[len - 1]).attr('src', ThinkPHP['ROOT'] + src).fadeIn();
            },50);
        },

       //在上传图片上的hover()
        hover:function () {
            var content=$('.weibo_pic_content');
            var len=content.length;
            $(content[len-1]).hover(function () {
               $(this).find('.remove').show();
               $(this).find('.text').show();
            },function () {
                $(this).find('.remove').hide();
                $(this).find('.text').hide();
            })
        },

       //在上传图片上的remove()
        remove:function () {
            var remove=$('.weibo_pic_content .text');
            var len=remove.length;
            $(remove[len-1]).on('click',function () {
                $(this).parent().next('input[name="image"]');
                $(this).parent().remove();
                lee_pic.uploadTotal--;
                lee_pic.uploadLimit++;
                $('.weibo_pic_total').text(lee_pic.uploadTotal);
                $('.weibo_pic_limit').text(lee_pic.uploadLimit);
            })
        },

        //最开始的自动执行方法，点击图片按钮，弹出上传图像框，执行上传图像方法。点击关闭按钮，隐藏框和箭头。点击空白处隐藏框。
        init : function () {
            $('#pic_btn').on('click', function () {
                var w = $(this).position();
                $('#pic_box').css({left:w.left-42,top:w.top+30}).show();
                $('.pic_arrow_top').show();
                lee_pic.uploadify();
            });
            $('#pic_box a.close').on('click',function(){
                $('#pic_box').hide();
                $('.pic_arrow_top').hide();
            });
            $(document).on('click',function(e){
                // event.target表示发生点击事件的元素；
                // this表示的是注册点击事件的元素
                // this 等于 e.currentTarget
                // this是所有函数原生具有的.进入函数时,this已经直接有了目标对象.
                //     而e.target通过e再寻找target,中转了一下。所以相比较而言，this的执行效率更高些。
                var target = $(e.target);
                //closest() 方法返回被选元素的第一个祖先元素。
                //点击target时找祖先元素#pic_btn,.weibo_pic_content .text,找到了返回.没有找到就下一步
                if( target.closest("#pic_btn").length == 1 || target.closest(".weibo_pic_content .text").length == 1)
                    return;
                //找不到#pic_box,隐藏图片框
                if( target.closest("#pic_box").length == 0 ){
                    $('#pic_box').hide();
                    $('.pic_arrow_top').hide();
                }
            });
        },
    };
    lee_pic.init();
    window.uploadCount = {
        clear : function () {
            lee_pic.uploadTotal = 0;
            lee_pic.uploadLimit = 8;
        }
    };
});
