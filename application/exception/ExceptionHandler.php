<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/16
 * Time: 16:56
 */
namespace app\exception;

use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    //定义返回的信息
    protected $code;
    protected $msg;
    protected $errorcode;
    //返回当前接口url
    //重写框架异常类(使用基类Exception，基类Exception是所有异常的基类)
    public function render(\Exception $ex){
        //判断是用户可以知道的还是服务端内部异常
        if($ex instanceof BaseException){
            //不同接口的自定义异常信息
            $this->code=$ex->code;
            $this->msg=$ex->msg;
            $this->errorcode=$ex->errorcode;
        }else{
            //对客户端、服务端进行控制
            if(!config('app_debug')){
                //代码问题的异常信息，不需要客户端知道为什么，但需要记录日志去解决问题
                $this->code=500;
                $this->msg='我的服务器内部错误';
                $this->errorcode=999;
                $this->Recordlog($ex);
            }else{
                //返回默认的异常信息页面
                return parent::render($ex);
            }
            
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
    //自定义日志：使用基类Exception，基类Exception是所有异常的基类
    private function Recordlog(\Exception $ex){
        Log::init([
            // 日志记录方式，内置 file socket 支持扩展
            'type'  => 'File',
            // 日志保存目录
            'path'  => LOG_PATH,
            // 日志记录级别
            'level' => ['error'],
        ]);
        Log::record($ex->getMessage(),'error');
    }
}