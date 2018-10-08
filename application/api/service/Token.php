<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 18:18
 */

namespace app\api\service;


class Token
{
    public static function generateToken(){
        //token=md5(32为长度的随机字符串(公共函数)+时间戳+盐)
        $randchars=getrandchars(32);
        $timestamp=$_SERVER['REQUEST_TIME_FLOAT'];
        $salt=config('secure.token_salt');
        return md5($randchars.$timestamp.$salt);
    }
}