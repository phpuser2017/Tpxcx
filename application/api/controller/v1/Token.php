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
use app\exception\ParamExcepotion;
use app\api\service\Token as TokenService;

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
    /**
     * 检验token
     * */
    public function checkToken($token=''){
        if(!$token){
            throw new ParamExcepotion(['token不能为空']);
        }
        $check=TokenService::ValidateToken($token);
        return json(['checktoken'=>$check]);
    }
}