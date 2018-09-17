<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/16
 * Time: 16:56
 */
namespace app\exception;

use think\Exception;
use think\exception\Handle;
use think\Request;

class ExceptionHandler extends Handle
{
    //定义返回的信息
    protected $code;
    protected $msg;
    protected $errorcode;
    //返回当前接口url
    //重写框架异常类
    public function render(Exception $ex){
        //判断是用户可以知道的还是服务端内部异常
        if($ex instanceof BaseException){
            //不同接口的自定义异常信息
            $this->code=$ex->code;
            $this->msg=$ex->msg;
            $this->errorcode=$ex->errorcode;
        }else{
            //代码问题的异常信息，不需要客户端知道为什么，但需要记录日志去解决问题
            $this->code=500;
            $this->msg='我的服务器内部错误';
            $this->errorcode=999;
        }
        $request=Request::instance();
        //组装返回的异常信息
        $re=[
            'msg'=>$this->msg,
            'errorcode'=>$this->errorcode,
            'url'=>$request->url()
        ];
        return json($re,$this->code);
    }
}