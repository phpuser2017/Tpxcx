<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/31
 * Time: 10:46
 */

namespace app\api\behavior;


class cors
{
    public function appInit(&$params){
        //解决跨域访问
        //允许所有域封为*
        header('Access-Control-Allow-Origin: *');
        //访问时允许携带的键名
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        //允许访问的方法
        header('Access-Control-Allow-Methods: POST,GET');
        if(request()->isOptions()){
            exit();
        }
    }
}