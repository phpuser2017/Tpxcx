<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/10/17
 * Time: 8:23
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
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
    }
}