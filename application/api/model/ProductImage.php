<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/10
 * Time: 11:22
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    //隐藏不需要的字段
    protected $hidden=['img_id','product_id','delete_time'];
    //和图片表一对一关联
    public function detailimg(){
        return $this->belongsTo('Image','img_id','id');
    }
}