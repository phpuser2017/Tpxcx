<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: fan
 * Date: 2018/09/25
 * Time: 14:56
=======
 * User: Fanji
 * Date: 2018/9/21
 * Time: 8:13
>>>>>>> 429fd28d766ff72f32d5cd8a476ddced02097709
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