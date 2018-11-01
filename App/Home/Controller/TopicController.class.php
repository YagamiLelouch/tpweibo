<?php
namespace Home\Controller;

class TopicController extends HomeController {
    //发布微博
    public function publish() {
        if (IS_AJAX) {
            //先发布一条微博
            $Topic = D('Topic');
            //session('user_auth')['id']为user的id
            $tid = $Topic->publish(I('post.content'), session('user_auth')['id']);
            if ($tid) {
                //不存在则返回空,不采取任何过滤
                $img = I('post.img', '', false);
                if (is_array($img)) {
                    $Image = D('Image');
                    $iid = $Image->storage($img, $tid);
                    echo $iid ? $tid : 0;
                } else {
                    echo $tid;
                }
            }
        } else {
            $this->error('非法访问！');
        }
    }

    //转发微博
    public function reBoardCast() {
        if (IS_AJAX) {
            $Topic = D('Topic');
            $tid = $Topic->publish(I('post.content'), session('user_auth')['id'], I('post.reid'));
            echo $tid;
        } else {
            $this->error('非法访问！');
        }
    }

    //Ajax获取微博列表
    public function ajaxList() {
        if (IS_AJAX) {
            $Topic = D('Topic');
            //第一次first为10
            $ajaxList = $Topic->getList(I('post.first'),10);
            $this->assign('ajaxList', $ajaxList);
            $this->display();
        } else {
            $this->error('非法访问！');
        }
    }

    //Ajax获取总页码  index主页自动执行
    public function ajaxCount() {
        if (IS_AJAX) {
            $Topic = D('Topic');
            //获取所有微博数量
            $count = $Topic->where('1=1')->count();
            //ceil — 进一法取整
            echo ceil($count / 10);
        } else {
            $this->error('非法访问！');
        }
    }






}