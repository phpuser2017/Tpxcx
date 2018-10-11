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
    //用户与用户地址一一对应
    public function getuseraddress(){
        return $this->hasOne('UserAddress','user_id','id');
    }
    //通过openid查询用户
    public static function getuserbyopenid($openid){
        $user=self::where('openid','=',$openid)->find();
        return $user;
    }
}