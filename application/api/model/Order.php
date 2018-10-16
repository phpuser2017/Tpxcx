<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/16
 * Time: 10:36
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden=['user_id','delete_time','update_time'];
}