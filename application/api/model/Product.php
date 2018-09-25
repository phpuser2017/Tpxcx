<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/25
 * Time: 14:56
 */

namespace app\api\model;


class Product extends BaseModel
{
    //隐藏不需要显示的字段
    protected $hidden=['create_time','update_time','delete_time','pivot','category_id'];
    public function getMainImgUrlAttr($value,$data){
        return $this->urlfix($value,$data);
    }
}