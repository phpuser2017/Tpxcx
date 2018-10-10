<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/10
 * Time: 11:22
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden=['product_id','update_time','delete_time'];
}