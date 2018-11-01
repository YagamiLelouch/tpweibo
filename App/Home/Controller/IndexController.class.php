<?php
namespace Home\Controller;

class IndexController extends HomeController {
    public function index(){
        if ($this->login()) {
            $Topic = D('Topic');
            //获取微博分页内容
            $topicList = $Topic->getList(0,10);
            //内容分配给topicList
            $this->assign('topicList', $topicList);
            //分配small图
            $User=D('User');
            $face=$User->getFace();
            $this->assign('smallFace', $face->small);
            //分配big图
            $this->assign('bigFace', $face->big);


            $this->display();
        }
    }
}