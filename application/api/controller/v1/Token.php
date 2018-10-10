<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 16:00
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\CodeCheck;

class Token
{
    //传入code获取token
    public function getToken($code=''){
        (new CodeCheck())->goCheck();
        $tokenservice=new UserToken($code);
        $token=$tokenservice->getopenid();
        return json([
            'code'=>1,
            'token'=>$token,
            'msg'=>'获取成功'
        ]);
    }
}