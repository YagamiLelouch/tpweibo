<?php
namespace Home\Model;
use Think\Model\RelationModel;

class ReferModel extends RelationModel {

    //微博表自动完成
    protected $_auto = array(
        array('create', 'time', self::MODEL_INSERT, 'function'),
    );

    //关联
    protected $_link = array(
        'topic'=>array(
            'mapping_type'=>self::BELONGS_TO,
            'class_name'=>'topic',
            'foreign_key'=>'tid',
            'mapping_fields'=>'content',
        ),
    );

    //@提醒到
    public function referTo($tid, $uid) {
        $data = array(
            'tid'=>$tid,
            'uid'=>$uid,
        );

        if ($this->create($data)) {
            $rid = $this->add();
            return $rid ? $rid : 0;
        } else {
            return $this->getError();
        }
    }

    //获取含有本uid的所有refer
    public function getRefer($uid) {
        $map['uid'] = $uid;
        return $this->relation(true)->field('id,tid,uid,read')->where($map)->select();
    }

    //设置阅读
    public function readRefer($id) {
        $map['id'] = $id;
        return $this->where($map)->save(array('read'=>1));
    }

    //获取@数量
    public function getReferCount($uid) {
        $map = array(
            'uid'=>$uid,
            'read'=>0,
        );
        return $this->where($map)->count();
    }

}