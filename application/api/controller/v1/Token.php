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
    public function getToken($code=''){
        (new CodeCheck())->goCheck();
        $tokenservice=new UserToken($code);
        $token=$tokenservice->tokenget();
        return $token;
    }
}