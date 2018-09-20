<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/19
 * Time: 17:19
 */

namespace app\api\model;

class Image extends BaseModel
{
    //显示需要显示的字段
    protected $visible=['url'];
    //获取需要处理的字段
    public function getUrlAttr($value,$data){
        //调用基类中图片域名处理方法
        return $this->urlfix($value,$data);
    }
}