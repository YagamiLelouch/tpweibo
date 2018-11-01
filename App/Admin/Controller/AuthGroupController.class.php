<?php
namespace Admin\Controller;

class AuthGroupController extends AuthController {
    //显示角色列表
    public function index(){
        $this->display();
    }

    //获取角色列表
    public function getList() {
        if (IS_AJAX) {
            $AuthGroup = D('AuthGroup');
            $this->ajaxReturn($AuthGroup->getList(I('post.page'),I('post.rows')));
        } else {
            $this->error('非法操作！');
        }
    }

    //获取所有角色
    public function getListAll() {
        if (IS_AJAX) {
            $AuthGroup = D('AuthGroup');
            $this->ajaxReturn($AuthGroup->getListAll());
        } else {
            $this->error('非法操作！');
        }
    }

    //新增角色
    public function addRole() {
        if (IS_AJAX) {
            $AuthGroup = D('AuthGroup');
            echo $AuthGroup->addRole(I('post.title'), I('post.rules'));
        } else {
            $this->error('非法操作！');
        }
    }


}