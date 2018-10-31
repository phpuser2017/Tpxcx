<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 16:00
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenValidate;
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
    /**
     * cms 登陆,第三方应用令牌获取
     * @ ac 账号
     * @ se 密码
     */
    public function getAppToken($ac='',$se=''){
        //解决跨域访问
        //允许所有域封为*
        header('Access-Control-Allow-Origin: *');
        //访问时允许携带的键名
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        //允许访问的方法
        header('Access-Control-Allow-Methods: GET');
        (new AppTokenValidate())->goCheck();
        $app=new AppToken();
        $token=$app->get($ac,$se);
        return json([
            'token'=>$token
        ]);
    }
}