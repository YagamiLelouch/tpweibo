<?php
/**
 * Created by PhpStorm.
 * User: wenhkd
 * Date: 2017/11/19
 * Time: 15:59
 */

namespace Admin\Controller;


use Think\Controller;

class LoginController extends Controller {
    public function index(){
        if(session('admin')){
            $this->redirect('Index/index');
        }else{
            $this->display();
        }
    }

    public function checkManager(){
        if(IS_AJAX){
            $Manage=D('Manage');
            $mid=$Manage->checkManager(I('post.manager'),I('post.password'));
            echo $mid;
        }else{
            $this->error('非法操作');
        }
    }

    public function out(){
        session('admin',null);
        $this->redirect('Login/index');
    }
}