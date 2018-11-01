<?php
namespace Home\Model;
use Think\Model;

class TopicModel extends Model\RelationModel {

    //微博表自动验证
    protected $_validate = array(
        //-1,'微博长度不合法！'
        array('allContent', '1,280', -1, self::EXISTS_VALIDATE,'length'),
    );

    //微博表自动完成
    protected $_auto = array(
        array('create', 'time', self::MODEL_INSERT, 'function'),
    );

    //一对一关联
    protected $_link=array(
        'images'=>array(
            'mapping_type'=>self::HAS_MANY,
            'class_name'=>'Image',
            'foreign_key'=>'tid',
        )
    );

    //发布微博和转发微博
    //$reid为转发源微博的id
    public function publish($allContent, $uid, $reid = 0) {
        //mb_strlen — 获取字符串的长度
        $len = mb_strlen($allContent, 'utf8');
        $content = $contentOver = '';
        //超过255
        if ($len > 255) {
            //mb_substr — 获取部分字符串
            $content = mb_substr($allContent, 0, 255, 'utf8');
            $contentOver = mb_substr($allContent, 255, 25, 'utf8');
        } else {
            $content = $allContent;
        }


        $data = array(
            'allContent'=>$allContent,
            'content'=>$content,
            'ip'=>get_client_ip(1),
            'uid'=>$uid,
        );

        if (!empty($contentOver)) {
            $data['content_over'] = $contentOver;
        }

        //如果为转发
        if ($reid > 0) {
            $data['reid'] = $reid;
        }


        if ($this->create($data)) {
            $tid = $this->add();
            //如果插入成功
            if ($tid) {
                //如果存在转发
                if ($reid > 0) $this->reCount($reid);
                //@提醒
                $this->refer($allContent, $tid);
                return $uid;
            } else {
                return 0;
            }
            //return $uid ? $uid : 0;
        } else {
            return $this->getError();
        }
    }

    //@提醒
    private function refer($content, $tid) {
        $pattern = '/(@\S+)\s/i';
        $content.=' ';
        preg_match_all($pattern, $content, $arr);

        if (!empty($arr[0])) {
            $User = D('user');
            $Refer = D('Refer');
            foreach ($arr[0] as $key=>$value) {
                $username = substr($value, 1);
                $uid = $User->getUser3($username)['id'];
                if ($uid) {
                    $rid = $Refer->referTo($tid, $uid);
                    if (!$rid) return $this->getError();
                }
            }
        }
    }

    //被转发的源微博+1
    private function reCount($reid) {
        $map['id'] = $reid;
        //setInc字段自增长,TP方法
        $this->where($map)->setInc('recount');
    }

    //获取微博列表,分页显示数据
    //index首页加载,下拉分页时调用
    public function getList($first, $total) {
     //查询到的数据format化
        return $this->format(
            //关联image数据库
            $this->relation(true)
            //双表查询
            ->table('__TOPIC__ a, __USER__ b')
            ->field('a.id,a.content,a.content_over,a.create,a.uid,a.reid,a.recount,b.username,b.face,b.domain')
                //$first起始位置,$total查询数量
            ->limit($first, $total)
            //安装创建时间降序排量
            ->order('a.create DESC')
            //用topic的uid和user的id连接数据表
            ->where('a.uid=b.id')
            ->select()
        );
    }
    //格式化微博数据
    public function format($list) {
        //遍历出每条topic,变成一个个单独的数组$value
        foreach ($list as $key=>$value) {
            //图片存在则执行
            if (!is_null($value['images'])) {
                //遍历每条topic的img
                foreach ($value['images'] as $key2=>$value2) {
                    //接受一个 JSON 编码的字符串并且把它转换为 PHP 变量
                    $value['images'][$key2] = json_decode($value2['data'], true);
                }
            }
            //一条条topic
            $list[$key] = $value;
            //每条topic的img数量
            $list[$key]['count'] = count($value['images']);

            //利用差值得秒钟
            $time = NOW_TIME - $list[$key]['create'];
            if ($time < 60) {
                $list[$key]['time'] = '刚刚发布';
                //60min内
            } else if ($time < 60 * 60) {
                $list[$key]['time'] = floor($time / 60).'分钟之前';
                // 格式化一个本地时间／日期. format写格式,后一个参数写需要格式化的时间或日期.后一个参数不写则按现在时间
                //今天
            } else if (date('Y-m-d') == date('Y-m-d', $list[$key]['create'])) {
                $list[$key]['time'] = '今天'.date('H:i', $list[$key]['create']);
                //-1天的时间和创建时间比较
            } else if (date("Y-m-d",strtotime("-1 day")) == date('Y-m-d',$list[$key]['create'])) {
                $list[$key]['time'] = '昨天'.date('H:i', $list[$key]['create']);
                //一年以内
            } else if ($time < 60 * 60 * 365) {
                $list[$key]['time'] = date('m月d日 H:i', $list[$key]['create']);
                //大于一年
            } else {
                $list[$key]['time'] = date('Y年m月d日 H:i', $list[$key]['create']);
            }

            //textarea专用，不转换
            $list[$key]['textarea'] = $list[$key]['content'];

            //表情解析
            //连接两段内容
            $list[$key]['content'] .= $list[$key]['content_over'];
            //content里面,符合pattern的括号分组内容的,被替换成括号分组的对应东西
            $list[$key]['content'] = preg_replace('/\[(a|b|c|d)_([0-9])+\]/i', '<img src="Public/'.MODULE_NAME.'/face/$1/$2.gif" border="0">', $list[$key]['content']);

            //解析@帐号
            //在content最后加一个空格
            $list[$key]['content'] .= ' ';
            $pattern = '/(@\S+)\s/i';
            //content里面,符合pattern的括号分组内容的,被替换成括号分组的对应东西,例如会换成@蜡笔小新
            $list[$key]['content'] = preg_replace($pattern, '<a href="'.__ROOT__.'/$1" class="space" target="_blank">$1</a>', $list[$key]['content']);

            //头像解析
            //mixed json_decode ( string $json [, bool $assoc = false [, int $depth = 512 [, int $options = 0 ]]] )
            //接受一个 JSON 编码的字符串并且把它转换为 PHP 变量.
            //assoc当该参数为 TRUE 时，将返回 array 而非 object
            $list[$key]['face'] = json_decode($list[$key]['face'])->small;

            //如果是转发的微博
            if ($list[$key]['reid'] > 0) {
                //得到被转发源微博格式化后的
                $list[$key]['recontent'] = $this->getReContent($list[$key]['reid']);
            }
        }
        return $list;
    }

    //获取被转播的微博内容
    private function getReContent($reid) {
        //format2格式化被转发的源微博的参数
        return $this->format2($this->relation(true)
            ->table('__TOPIC__ a, __USER__ b')
            ->field('a.id,a.content,a.content_over,a.create,a.uid,a.reid,a.recount,b.username,b.face,b.domain')
            ->where('a.uid=b.id AND a.id='.$reid)
            ->find());
    }

    //格式化被转发的微博
    //一条微博
    private function format2($list) {
        if (!is_null($list['images'])) {
            foreach ($list['images'] as $key=>$value) {
                $list['images'][$key] = json_decode($value['data'], true);
            }
        }
        $list['count'] = count($list['images']);

        //表情解析
        $list['content'] .= $list['content_over'];
        $list['content'] = preg_replace('/\[(a|b|c|d)_([0-9])+\]/i', '<img src="'.__ROOT__.'/Public/'.MODULE_NAME.'/face/$1/$2.gif" border="0">', $list['content']);

        //解析@帐号
        $list['content'] .= ' ';
        $pattern = '/(@\S+)\s/i';
        $list['content'] = preg_replace($pattern, '<a href="'.__ROOT__.'/$1" class="space" target="_blank">$1</a>', $list['content']);

        //原微博时间
        $time = NOW_TIME - $list['create'];
        if ($time < 60) {
            $list['time'] = '刚刚发布';
        } else if ($time < 60 * 60) {
            $list['time'] = floor($time / 60).'分钟之前';
        } else if (date('Y-m-d') == date('Y-m-d', $list['create'])) {
            $list['time'] = '今天'.date('H:i', $list['create']);
        } else if (date("Y-m-d",strtotime("-1 day")) == date('Y-m-d',$list['create'])) {
            $list['time'] = '昨天'.date('H:i', $list['create']);
        } else if (date('Y') == date('Y', $list['create'])) {
            $list['time'] = date('m月d日 H:i', $list['create']);
        } else {
            $list['time'] = date('Y年m月d日 H:i', $list['create']);
        }


        return $list;
    }

}