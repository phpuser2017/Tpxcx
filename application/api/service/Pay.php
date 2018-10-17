<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/10/17
 * Time: 8:29
 */

namespace app\api\service;


use think\Exception;

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
}