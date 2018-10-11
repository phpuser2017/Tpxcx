<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 18:18
 */

namespace app\api\service;


use app\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    //生成token
    public static function generateToken(){
        //token=md5(32为长度的随机字符串(公共函数)+时间戳+盐)
        $randchars=getrandchars(32);
        $timestamp=$_SERVER['REQUEST_TIME_FLOAT'];
        $salt=config('secure.token_salt');
        return md5($randchars.$timestamp.$salt);
    }
    /*根据具体参数限定利用token获取用户的什么信息
     * @result (session_key、expires_in、openid)通过code获取的微信信息
     * @uid 用户id
     * @scope   自定义权限级别
     * */
    public static function getTokenVal($key){
        //从请求头获取用户传递的token
        $gettoken=Request::instance()->header('token');
        //在缓存中获取token对应的用户信息
        $info=Cache::get($gettoken);
        //判断是否获取到token对应的信息
        if(!$info){
            throw new TokenException();
        }else{
            //判断获取到的信息是否为数组
            if(!is_array($info)){
                //转为数组方便后续操作
                $info=json_decode($info,true);
            }
            //检查传入的参数键是否存在
            if(array_key_exists($key,$info)){
                return $info[$key];
            }else{
                throw new Exception('要获取的Token变量不存在');
            }
        }
    }
    //利用token获取当前用户uid
    public static function getCurrentUid(){
        $uid=self::getTokenVal('uid');
        return $uid;
    }
}