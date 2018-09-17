<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/16
 * Time: 18:08
 */

namespace app\exception;


use think\Exception;

class BaseException extends Exception
{
    public $code=400;
    public $msg='参数错误';
    public $errorcode=10000;
    
    //构造函数将自定义错误信息重写
    public function __construct($param=[])
    {
        //是否已数组形式传递的错误信息
        if(!is_array($param)){
            return;
        }
        //存在自定义的错误信息进行重写
        if(array_key_exists('code',$param)){
            $this->code=$param['code'];
        }
        if(array_key_exists('msg',$param)){
            $this->msg=$param['msg'];
        }
        if(array_key_exists('errorcode',$param)){
            $this->errorcode=$param['errorcode'];
        }
    }
}