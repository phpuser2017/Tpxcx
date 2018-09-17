<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/15
 * Time: 22:03
 */

namespace app\api\validate;


use app\exception\ParamExcepotion;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取参数
        $request=Request::instance();
        $params=$request->param();
        //批量验证参数
        $result=$this->batch()->check($params);
        if(!$result){
            //获取具体的验证错误信息自定义并抛出
            $err=new ParamExcepotion([
                'msg'=>$this->error,
                'code'=>400,
                'errorcode'=>10002
            ]);
//            $err->msg=$this->error;
            throw $err;
        }else{
            return true;
        }
    }
}