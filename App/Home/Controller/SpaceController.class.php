<?php
namespace Home\Controller;

class SpaceController extends HomeController {
    //显示主页
    public function index($id = 0, $domain = ''){
        if ($id == 0 && $domain == '') $this->error('非法访问！');
        if ($this->login()) {
            $User = D('User');
            if ($id) {
                $getUser = $User->getUser($id);
                if ($getUser) {
                    $this->assign('user', $getUser);
                    $this->assign('bigFace', json_decode($getUser['face'])->big);
                    $this->display();
                } else {
                    $this->error('不存在此用户！');
                }
            }
            if ($domain) {
                $getUser = $User->getUser2($domain);
                if ($getUser) {
                    $this->assign('user', $getUser);
                    $this->assign('bigFace', json_decode($getUser['face'])->big);
                    $this->display();
                } else {
                    $this->error('不存在此用户！');
                }
            }
        }
    }

    //设置URL
    public function setUrl($username = '') {
        if (IS_AJAX && $username != '') {
            $User = D('User');
            $getUser = $User->getUser3($username);
            if (is_array($getUser)) {
                //域名为空
                if (empty($getUser['domain'])) {
                    echo U('Space/index', array('id'=>$getUser['id']));
                    //域名不为空
                } else {
                    echo __ROOT__.'/i/'.$getUser['domain'];
                }
            }
        } else {
            $this->error('非法访问！');
        }
    }
}