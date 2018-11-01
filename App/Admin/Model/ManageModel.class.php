<?php
namespace Admin\Model;
use Think\Model;
use Think\Auth;

class ManageModel extends Model {

    //管理员帐号自动验证
    protected $_validate = array(
        //-1,'帐号长度不合法！'
        array('manager', '/^[^@]{2,20}$/i', -1, self::EXISTS_VALIDATE),
        //-2,'密码长度不合法！'
        array('password', '6,30', -2, self::EXISTS_VALIDATE,'length'),
    );

    //用户表自动完成
    protected $_auto = array(
        array('password', 'sha1', self::MODEL_BOTH, 'function'),
        array('create', 'time', self::MODEL_INSERT, 'function'),
    );

    //获取管理员列表
    public function getList($page, $rows, $order, $sort) {
        $obj = $this->field('id,manager,create,last_login,last_ip')
            ->order(array($sort=>$order))
            ->limit(($rows * ($page - 1)), $rows)
            ->select();
        foreach ($obj as $key=>$value) {
            $obj[$key]['create'] = date('Y-m-d H:i:s', $value['create']);
            $obj[$key]['last_login'] = date('Y-m-d H:i:s', $value['last_login']);
            $obj[$key]['last_ip'] = long2ip($value['last_ip']);

            $Auth = new Auth();
            $obj[$key]['role'] = $Auth->getGroups($value['id'])[0]['title'];

        }
        return array(
            'total'=>$this->count(),
            'rows'=>$obj ? $obj : '',
        );
    }

    //验证管理员登录
    public function checkManager($manager, $password) {
        $data = array(
            'manager'=>$manager,
            'password'=>$password,
        );

        if ($this->create($data)) {
            $map['manager'] = $manager;
            $map['password'] = sha1($password);
            $obj = $this->field('id,manager')->where($map)->find();
            if ($obj) {
                session('admin', array(
                    'id'=>$obj['id'],
                    'manager'=>$obj['manager'],
                ));
                //登录验证后写入登录信息
                $update = array(
                    'id'=>$obj['id'],
                    'last_login'=>NOW_TIME,
                    'last_ip'=>get_client_ip(1),
                );
                $this->save($update);
                return $obj['id'];
            } else {
                return 0;
            }
        } else {
            return $this->getError();
        }
    }

    //新增管理员
    public function addManage($Manager, $password, $role) {
        $data = array(
            'manager'=>$Manager,
            'password'=>$password,
        );

        if ($this->create($data)) {
            $mid = $this->add();
            if ($mid) {
                $data = array(
                    'uid'=>$mid,
                    'group_id'=>$role,
                );

                $AuthGroupAccess = M('AuthGroupAccess');
                $AuthGroupAccess->add($data);

                return $mid;
            } else {
                return 0;
            }
        } else {
            return $this->getError();
        }
    }
}