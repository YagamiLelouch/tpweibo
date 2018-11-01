<?php
/**
 * Created by PhpStorm.
 * User: wenhkd
 * Date: 17.10.30
 * Time: 20:12
 */

namespace Home\Model;


use Think\Model;

class ImageModel extends Model {
    public function storage($img,$tid){
        foreach ($img as $key=>$value) {
            $data = array(
                'data'=>$value,
                'tid'=>$tid,
            );
            if (!$this->add($data)) {
                return 0;
            }
        }
        return 1;
    }
}