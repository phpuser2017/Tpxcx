<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/12
 * Time: 15:41
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\OrderParamCheck;

class Order extends BaseController
{
    //只要用户可以创建订单
    protected $beforeActionList=[
        'NeedUser'=>['only'=>'CreateOrder']
    ];
    //创建订单
    public function CreateOrder(){
        (new OrderParamCheck())->goCheck();
        //用户选择商品，将选择的商品信息提交到订单api--参数：商品id、商品数量
        
        //接收到选择的商品信息检查提交的商品的库存量
        //​	有库存
        //​		将订单数据存入数据库，下单成功
        //​		再次检测库存，库存正常则调用微信支付
        //​		在调用微信支付至微信支付返回结果间再次检测库存
        //      根据微信支付结果对库存量进行操作
        //​		成功：减少库存，
        //​	无库存
        
    }
}