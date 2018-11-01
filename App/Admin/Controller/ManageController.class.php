<?php
namespace Admin\Controller;

class ManageController extends AuthController {
    //显示管理员列表
    public function index(){
        $this->display();
    }

    //获取管理员列表
    public function getList() {
        if (IS_AJAX) {
            $Manage = D('Manage');
            $this->ajaxReturn($Manage->getList(I('post.page'),I('post.rows'),I('post.order'), I('post.sort')));
        } else {
            $this->error('非法操作！');
        }
    }

    //新增管理员
    public function addManage() {
        if (IS_AJAX) {
            $Manage = D('Manage');
            echo $Manage->addManage(I('post.manager'), I('post.password'), I('post.role'));
        } else {
            $this->error('非法操作！');
        }
    }
}