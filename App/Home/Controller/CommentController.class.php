<?php
namespace Home\Controller;

class CommentController extends HomeController {
    //发布评论
    public function publish() {
        if (IS_AJAX) {
            $Comment = D('Comment');
            //session('user_auth')['id']传当前用户id
            $cid = $Comment->publish(I('post.content'), session('user_auth')['id'], I('post.tid'));
            echo $cid;
        } else {
            $this->error('非法访问！');
        }
    }

    //获取评论列表
    public function getList() {
        if (IS_AJAX) {
            $Comment = D('Comment');
            //微博id,评论页数
            $getList = $Comment->getList(I('post.tid'), I('post.page'));
            //格式化后的所有数据
            $this->assign('getList', $getList['list']);
            //总页数,用来for循环
            $this->assign('total', $getList['total']);
            //当前页数
            $this->assign('page', I('post.page'));
            $this->display();
        } else {
            $this->error('非法访问！');
        }
    }

}