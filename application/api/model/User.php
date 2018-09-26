<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 17:57
 */

namespace app\api\model;


class User extends BaseModel
{
    public static function getuserbyopenid($openid){
        $user=self::where('openid','=',$openid)->find();
        return $user;
    }
}