<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/30
 * Time: 14:09
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
    //根据输入的账号密码查询数据库是否存在该账号密码
    public static function check($ac,$se){
        $app=self::where('app_id','=',$ac)->where('app_secret','=',$se)->find();
        return $app;
    }
}