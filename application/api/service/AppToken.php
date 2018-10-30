<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/30
 * Time: 14:05
 */

namespace app\api\service;


use app\api\model\ThirdApp;
use app\exception\TokenException;

class AppToken extends Token
{
    public function get($ac,$se){
        $app=ThirdApp::check($ac,$se);
        if(!$app){
            throw new TokenException([
                'msg'=>'app授权失败',
                'errorcode'=>10004
            ]);
        }else{
            //账号、密码存在获取数据库中权限、id存入缓存
            $scope=$app->scope;
            $uid=$app->id;
            $val=[
                'scope'=>$scope,
                'uid'=>$uid
            ];
            //产生token并存入缓存
            $apptoken=$this->saveToCache($val);
            return $apptoken;
        }
    }
    private function saveToCache($value){
        $token=self::generateToken();
        $time=config('setting.token_vld');
        $cache=cache($token,json_encode($value),$time);
        if(!$cache){
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'errorcode'=>10005
            ]);
        }
        return $token;
    }
}