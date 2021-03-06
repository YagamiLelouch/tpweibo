<?php
namespace Home\Controller;
use Think\Controller;

class HomeController extends Controller {


    //构造方法
    protected function _initialize() {

    }

    //通过Aajx轮询执行方法
    public function getRefer() {
        if (IS_AJAX) {
            $Refer = D('Refer');
            $referCount = $Refer->getReferCount(session('user_auth')['id']);
            echo $referCount;
        } else {
            $this->error('非法操作！');
        }
    }

    //检测用户登录状态
	protected function login() {
		
		//处理自动登录，当cookie存在，且session不存在的情况下，生成session
		if (!is_null(cookie('auto')) && !session('?user_auth')) {
		    //explode — 使用一个字符串分割另一个字符串
            //type=1时，解码
			$value = explode('|', encryption(cookie('auto'), 1));
			//把数组中的值赋给一组变量
			list($username, $ip) = $value;
			
			if ($ip == get_client_ip()) {
				$map['username'] = $username;
                $User = D('User');
                $userObj = $User->field('id,username')->where($map)->find();

                //自动登录验证后写入登录信息
                //更新登陆信息
                $update = array(
                    'id'=>$userObj['id'],
                    'last_login'=>NOW_TIME,
                    'last_ip'=>get_client_ip(1),
                );
                $User->save($update);

                //将记录写入到cookie和session中去
                $auth = array(
                    'id'=>$userObj['id'],
                    'username'=>$userObj['username'],
                    'last_login'=>NOW_TIME,
                );

                //写入到session
                session('user_auth', $auth);
			}
		}
		
		
		//检测session是否存在
		if (session('?user_auth')) {
			return 1;
		} else {
			$this->redirect('Login/index');
		}
	}
}