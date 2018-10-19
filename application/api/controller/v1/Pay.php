<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/10/17
 * Time: 8:23
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\Idvaliadet;
use app\api\service\Pay as PayService;
class Pay extends BaseController
{
    //验证用户权限的前置操作
    protected $beforeActionList=[
        'NeedUser'=>['only'=>'PrePayOrder']
    ];
    //下单-支付
    public function PrePayOrder($id=''){
        (new Idvaliadet())->goCheck();
        $pay=new PayService($id);
        return $pay->Pay();
    }
    /**
     * 微信支付通知回调[必须为post方式，返回格式xml，路由地址不能携带参数]
     * 在服务器未返回给微信服务器正确响应结果时微信服务器会以一定频率调用回调接口
     * 频率：15/15/30/180/1800/1800/1800/3600 秒
     * */
    public function ReceiveNotify(){
        //1、检查库存量
        //2、更新订单状态status
        //3、库存减少
        //成功，给微信服务器返回正确响应；失败，给微信服务器返回错误响应
        $wxpaynotify=new WxNotify();
        $wxpaynotify->Handle();
    }
    /**
     * 调试支付回调使用
     * */
   /* public function redirectNotify(){
        //1、检查库存量
        //2、更新订单状态status
        //3、库存减少
        //成功，给微信服务器返回正确响应；失败，给微信服务器返回错误响应
        $wxpaynotify=new WxNotify();
        $wxpaynotify->Handle();
    }
    public function ReceiveNotify(){
        //访问请求的原始数据的只读流,获取微信服务器返回的xml数据
        $xmlData=file_get_contents('php://input');
        //将数据转发到回调处理方法
        $result=curl_post($_SERVER['SERVER_NAME'].'api/v1/pay/wxpaynotify?XDEBUG_SESSION_START=13408',$xmlData,['Content-Type:text']);
    }*/
}