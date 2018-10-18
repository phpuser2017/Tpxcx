<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/18
 * Time: 15:19
 */

namespace app\api\service;

use app\api\model\Product;
use app\enum\OrderStateEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'Api.php');
class WxNotify extends \WxPayNotify
{
    /**
     * 微信支付回掉
     *      检查库存量
     *      更新订单状态status
     *      库存减少
     *      成功，给微信服务器返回正确响应；失败，给微信服务器返回错误响应
     * sdk将返回的xml转换为数组格式$objData
     * */
    public function NotifyProcess($objData, $config, &$msg)
    {
        //result_code  业务结果（SUCCESS/FAIL）
        if($objData['result_code']=='SUCCESS'){
            Db::startTrans();
            try{
                //支付成功 检查库存量、更新订单状态status、库存减少
                $oderNo=$objData['out_trade_no'];
                $order=OrderModel::where('order_no','=',$oderNo)->lock(true)->find();
                if($order->status==1){
                    $orderService=new OrderService();
                    //检测库存
                    $orederStockStatus=$orderService->PayCheckOrderStock($order->id);
                    //库存检测是否通过
                    if($orederStockStatus['pass']){
                        //库存检测通过
                        //更新订单状态(设为已付款)
                        $this->updateOrderstatus($order->id,true);
                        //减少订单中对应商品的库存数量
                        $this->reduceProductStock($orederStockStatus);
                    }else{
                        //库存检测未通过
                        //支付成功订单状态改为：支付成功但库存不足状态
                        $this->updateOrderstatus($this->id,false);
                    }
                }
                Db::commit();
                return true;
            }catch (Exception $ex){
                Db::rollback();
                Log::error($ex);
                return false;
            }
        }else{
            //支付失败，给微信服务器返回true使之停止异步回调
            return true;
        }
    }
    /**
     * 减少订单中对应商品的库存数量
     * */
    private function reduceProductStock($orederStockStatus){
        //将订单中商品对应库存减少
        foreach ($orederStockStatus['pdetailArray'] as $product){
            Product::where('id','=',$product['id'])->setDec('stock',$product['pcount']);
        }
    }
    /**
     * 更新订单状态
     * */
    private function updateOrderstatus($orderid,$success){
        //库存检测通过，订单状态改为支付成功；未通过，订单状态改为支付成功但库存不足状态
        $orderStatus=$success ? OrderStateEnum::Paid : OrderStateEnum::PaidNoStock;
        OrderModel::where('id','=',$orderid)->update(['status'=>$orderStatus]);
    }
}