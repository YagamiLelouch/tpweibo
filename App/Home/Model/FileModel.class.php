<?php
namespace Home\Model;
use Think\Model;
use Think\Upload;
use Think\Image;

class FileModel extends Model {
    //微博图片上传
    public function image() {
        //实例化上传类
        $Upload = new Upload();
        //设置附件上传根目录
        $Upload->rootPath = C('UPLOAD_PATH');
        // 设置附件上传大小
        $Upload->maxSize = 1048579;
        // 上传文件
        $info = $Upload->upload();

        //如果上传成功
        if ($info) {
            //上传文件的保存路径
            $savePath = $info['Filedata']['savepath'];
            //上传文件的保存名称
            $saveName = $info['Filedata']['savename'];
            //上传图片的路径
            $imgPath = C('UPLOAD_PATH').$savePath.$saveName;
            //实例化图片处理类
            $image = new Image();
            //打开图片文件
            $image->open($imgPath);
            //设置缩略图文件名
            $thumbPath = C('UPLOAD_PATH').$savePath.'180_'.$saveName;
            //保存缩略图
            $image->thumb(180, 180)->save($thumbPath);

            $image->open($imgPath);
            $unfoldPath = C('UPLOAD_PATH').$savePath.'550_'.$saveName;
            $image->thumb(550, 550)->save($unfoldPath);
            $imageArr = array(
                'thumb'=>$thumbPath,
                'unfold'=>$unfoldPath,
                'source'=>$imgPath,
            );
            return $imageArr;
        } else {
            return $Upload->getError();
        }
    }

    //个人头像上传
    public function face() {
        $Upload = new Upload();
        $Upload->rootPath = C('UPLOAD_PATH');
        $Upload->maxSize = 1048579;
        $info = $Upload->upload();
        if ($info) {
            $savePath = $info['Filedata']['savepath'];
            $saveName = $info['Filedata']['savename'];
            $imgPath = C('UPLOAD_PATH').$savePath.$saveName;
            $image = new Image();
            $image->open($imgPath);
            $image->thumb(500, 500)->save($imgPath);
            return $imgPath;
        } else {
            return $Upload->getError();
        }
    }

    //保存头像
    public function crop($url, $x, $y, $w, $h) {
        $bigPath = C('FACE_PATH').session('user_auth')['id'].'.jpg';
        $smallPath = C('FACE_PATH').session('user_auth')['id'].'_small.jpg';
        $image = new Image();
        $image->open($url);
        $image->crop($w, $h, $x, $y)->save($url);
        $image->thumb(200, 200, Image::IMAGE_THUMB_FIXED)->save($bigPath);
        $image->thumb(50, 50, Image::IMAGE_THUMB_FIXED)->save($smallPath);
        $imageArr = array(
            'big'=>$bigPath,
            'small'=>$smallPath,
        );
        return $imageArr;
    }




}