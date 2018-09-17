<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/15
 * Time: 22:03
 */

namespace app\api\validate;


use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取参数
        $request=Request::instance();
        $params=$request->param();
        //验证参数
        $result=$this->check($params);
        if(!$result){
            //获取错误信息并抛出
            $message=$this->error;
            throw new Exception($message);
        }else{
            return true;
        }
    }
}