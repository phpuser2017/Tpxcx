<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/10/17
 * Time: 8:29
 */

namespace app\api\service;


use app\enum\OrderStateEnum;
use app\exception\OrderException;
use app\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Loader;
use think\Log;

/**
 *引入微信支付sdk
 *  引入 extend/wechat-sdk/wechat.class.php
 *  Loader::import('wechat-sdk.wechat', EXTEND_PATH, '.class.php');
*/
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderId;
    private $orderNo;
    function __construct($orderid)
    {
        if(!$orderid){
            throw new Exception('订单号不能为NULL');
        }
        $this->orderId=$orderid;
    }
    public function Pay(){
        //对orderid进行业务逻辑检测
        $this->OrderIdValidate();
        //调用orderservice中的订单商品库存检测方法
        $OrderService=new OrderService();
        $orderstates=$OrderService->PayCheckOrderStock($this->orderId);
        if(!$orderstates['pass']){
            return $orderstates;
        }
        //创建微信预订单
        return $this->CretaeWxPreOrder($orderstates['orderprice']);
    }
    /**
     *创建微信预订单
     */
    private function CretaeWxPreOrder($totalprice){
        //获取用户openid
        $useropenid=Token::getTokenVal('openid');
        if(!$useropenid){
            throw new OrderException();
        }
        //实例化微信sdk统一下单输入对象(将支付参数封装为一个对象)
        $WxOrderData=new \WxPayUnifiedOrder();
        $WxOrderData->SetOut_trade_no($this->orderNo);//商户系统内部订单号
        $WxOrderData->SetTrade_type('JSAPI');//交易类型 JSAPI 公众号支付
        $WxOrderData->SetTotal_fee($totalprice*100);//订单总金额
        $WxOrderData->SetBody('Tp5小程序');//商品简单描述
        $WxOrderData->SetOpenid($useropenid);//用户标识，微信用户在商户对应appid下的唯一标识
        $WxOrderData->SetNotify_url('http://qq.com');//异步接收微信支付结果通知的回调地址(通知url必须为外网可访问的url，不能携带参数)。
        //获取微信预支付订单
        return $this->getWxPreOrder($WxOrderData);
    }
    /**
     *获取微信预支付订单
     * */
    private function getWxPreOrder($WxOrderData){
        //统一下单
        $config=new \WxPayConfig();
        $wxOrder=\WxPayApi::unifiedOrder($config,$WxOrderData);
        if($wxOrder['return_code']!='SUCCESS' || $wxOrder['result_code']!='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('微信预支付订单获取失败','error');
        }
        return 'wxpay123';
    }
    /**
     *对orderid进行业务逻辑检测
    */
    public function OrderIdValidate(){
        //订单号可能不存在
        $orderCheck=OrderModel::Where('id','=',$this->orderId)->find();
        if(!$orderCheck){
            throw new OrderException();
        }
        //订单号存在，订单号与操作用户不匹配
        $UidCheck=Token::ValidetOperation($orderCheck->user_id);
        if(!$UidCheck){
            throw new TokenException([
                'msg'=>'订单与用户不匹配',
                'errorcode'=>10003
            ]);
        }
        //订单是否被支付
        if($orderCheck->status!=OrderStateEnum::Unpaid){
            throw new OrderException([
                'msg'=>'订单已被支付，请勿重复支付',
                'errorcode'=>80003,
                'code'=>400
            ]);
        }
        //获取订单编号
        $this->orderNo=$orderCheck->order_no;
        return true;
    }
}