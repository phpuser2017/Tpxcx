<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/11
 * Time: 15:12
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    //隐藏不需要显示的字段
    protected $hidden=['id','update_time','delete_time'];
}