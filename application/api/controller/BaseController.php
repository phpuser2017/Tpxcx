<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/12
 * Time: 16:10
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\UserToken as TokenService;

class BaseController extends Controller
{
    //基础权限验证
    protected function CheckBaseScope(){
        TokenService::BaseScope();
    }
    //用户级权限验证
    protected function NeedUser(){
        TokenService::USerScope();
    }
    
}